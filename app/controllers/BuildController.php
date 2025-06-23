<?php

require_once BASE_PATH . 'app/controllers/BaseController.php';

class BuildController extends BaseController
{
    protected $productModel;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->productModel = new Product($pdo);
    }

    /**
     * Displays the PC build configuration page.
     * Fetches components categorized for selection.
     */
    public function index()
    {
        $categories = [
            'CPU', 'GPU', 'Motherboard', 'RAM', 'Storage', 'PSU', 'Case'
        ];

        $components = [];
        foreach ($categories as $category) {
            $components[$category] = $this->productModel->getProductsByCategory($category);
        }

        $data = [
            'title' => 'Build Your PC & Get a Rating',
            'components' => $components
        ];

        $this->view('build/index', $data);
    }

    /**
     * Calculates and displays the build rating based on selected components.
     * This method expects POST data with component IDs.
     */
    public function getRating()
    {
        header('Content-Type: application/json'); // Respond with JSON

        // Get selected component IDs from POST request
        $selectedComponentIds = $_POST['components'] ?? [];

        if (empty($selectedComponentIds)) {
            echo json_encode(['success' => false, 'message' => 'No components selected for rating.']);
            http_response_code(400);
            exit();
        }

        $selectedProducts = [];
        $totalPrice = 0;

        foreach ($selectedComponentIds as $id) {
            $product = $this->productModel->getProductById($id);
            if ($product) {
                $selectedProducts[] = $product;
                $totalPrice += $product['price'];
            }
        }

        if (empty($selectedProducts)) {
            echo json_encode(['success' => false, 'message' => 'Selected components could not be found.']);
            http_response_code(400);
            exit();
        }

        // --- Build Rating Logic (Simplified for now) ---
        // This is a basic rule-based system.
        // You can make this much more complex and intelligent.
        $ratingScore = 0; // Max 100
        $ratingComments = [];
        $hasCPU = false;
        $hasGPU = false;
        $hasMotherboard = false;
        $hasRAM = false;
        $hasPSU = false;
        $totalPowerConsumptionEstimate = 0; // Very rough estimate for now

        $cpuTier = 0; // 1-low, 2-mid, 3-high
        $gpuTier = 0;

        // Process each selected product
        foreach ($selectedProducts as $product) {
            switch ($product['category']) {
                case 'CPU':
                    $hasCPU = true;
                    // Simple tiering based on name for demonstration
                    if (str_contains($product['name'], 'i9') || str_contains($product['name'], 'Ryzen 9')) {
                        $cpuTier = 3;
                    } elseif (str_contains($product['name'], 'i7') || str_contains($product['name'], 'Ryzen 7') || str_contains($product['name'], 'i5') || str_contains($product['name'], 'Ryzen 5')) {
                        $cpuTier = 2;
                    } else {
                        $cpuTier = 1;
                    }
                    // Estimate power usage
                    $totalPowerConsumptionEstimate += 150; // Example
                    break;
                case 'GPU':
                    $hasGPU = true;
                    // Simple tiering based on name for demonstration
                    if (str_contains($product['name'], '4090') || str_contains($product['name'], '4080') || str_contains($product['name'], '3090') || str_contains($product['name'], '3080') || str_contains($product['name'], 'RX 7900')) {
                        $gpuTier = 3;
                    } elseif (str_contains($product['name'], '4070') || str_contains($product['name'], '3070') || str_contains($product['name'], '3060') || str_contains($product['name'], 'RX 6700')) {
                        $gpuTier = 2;
                    } else {
                        $gpuTier = 1;
                    }
                    // Estimate power usage
                    $totalPowerConsumptionEstimate += 300; // Example
                    break;
                case 'Motherboard':
                    $hasMotherboard = true;
                    break;
                case 'RAM':
                    $hasRAM = true;
                    // Check RAM quantity/speed for bonus points
                    if (str_contains($product['name'], '32GB') || str_contains($product['name'], '64GB')) {
                        $ratingScore += 5;
                    }
                    if (str_contains($product['name'], '6000MHz') || str_contains($product['name'], 'DDR5')) {
                        $ratingScore += 5;
                    }
                    break;
                case 'PSU':
                    $hasPSU = true;
                    $psuWattage = (int)filter_var($product['name'], FILTER_SANITIZE_NUMBER_INT); // Extract numbers from PSU name
                    if ($psuWattage > 0) {
                        if ($psuWattage >= $totalPowerConsumptionEstimate * 1.2) { // PSU 20% more than estimate
                            $ratingScore += 10;
                            $ratingComments[] = "Power supply seems adequate for estimated power draw ({$psuWattage}W vs ~{$totalPowerConsumptionEstimate}W).";
                        } else {
                            $ratingComments[] = "Warning: Power supply (estimated {$psuWattage}W) might be insufficient for estimated power draw (~{$totalPowerConsumptionEstimate}W). Consider a higher wattage PSU.";
                            $ratingScore -= 10;
                        }
                    }
                    break;
                case 'Storage':
                case 'Case':
                    // These generally don't affect performance rating directly, but are necessary
                    break;
            }
        }

        // Basic component presence check
        if ($hasCPU) $ratingScore += 10; else $ratingComments[] = "Missing CPU.";
        if ($hasGPU) $ratingScore += 10; else $ratingComments[] = "Missing GPU.";
        if ($hasMotherboard) $ratingScore += 10; else $ratingComments[] = "Missing Motherboard.";
        if ($hasRAM) $ratingScore += 10; else $ratingComments[] = "Missing RAM.";
        if ($hasPSU) $ratingScore += 10; else $ratingComments[] = "Missing Power Supply.";

        // Basic compatibility check (CPU/GPU synergy)
        if ($cpuTier === 1 && $gpuTier === 3) {
            $ratingComments[] = "Potential Bottleneck: High-end GPU with a low-end CPU. Consider upgrading the CPU.";
            $ratingScore -= 15;
        } elseif ($cpuTier === 3 && $gpuTier === 1) {
            $ratingComments[] = "Potential Bottleneck: High-end CPU with a low-end GPU. Consider upgrading the GPU.";
            $ratingScore -= 15;
        } elseif ($cpuTier === 1 && $gpuTier === 2) {
             $ratingComments[] = "Potential Bottleneck: Mid-range GPU with a low-end CPU. Consider a better CPU.";
             $ratingScore -= 5;
        }

        // Ensure rating is within 0-100 range
        $ratingScore = max(0, min(100, $ratingScore));

        // Generate a qualitative description based on the score
        $qualitativeRating = "Your build looks good!";
        if ($ratingScore < 30) {
            $qualitativeRating = "This build has significant issues. Please review missing or incompatible components.";
        } elseif ($ratingScore < 60) {
            $qualitativeRating = "This is a decent start, but there are areas for improvement.";
        } elseif ($ratingScore < 80) {
            $qualitativeRating = "A solid build, capable of good performance!";
        } elseif ($ratingScore >= 90) {
            $qualitativeRating = "Excellent build! Well-balanced and powerful.";
        }

        // Prepare response
        $response = [
            'success' => true,
            'rating' => $ratingScore,
            'qualitative_rating' => $qualitativeRating,
            'comments' => $ratingComments,
            'total_price' => $totalPrice,
            'selected_products' => $selectedProducts // For debugging or display
        ];

        echo json_encode($response);
        exit();
    }
}
