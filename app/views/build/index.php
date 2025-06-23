<div class="container mx-auto p-8 bg-white shadow-lg rounded-lg my-8 max-w-4xl">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-6 text-center"><?php echo htmlspecialchars($title); ?></h1>

    <p class="text-center text-gray-700 mb-8">Select your desired PC components below and click "Get Build Rating" to see how well they perform together!</p>

    <form id="build-form" class="space-y-6">
        <?php foreach ($components as $category => $products): ?>
            <div class="border border-gray-200 rounded-lg p-4 shadow-sm">
                <label for="<?php echo strtolower($category); ?>" class="block text-xl font-semibold text-gray-800 mb-3"><?php echo htmlspecialchars($category); ?>:</label>
                <select id="<?php echo strtolower($category); ?>" name="<?php echo strtolower($category); ?>"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-base bg-white"
                        onchange="updateBuildSummary()">
                    <option value="">-- Select a <?php echo htmlspecialchars($category); ?> --</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?php echo htmlspecialchars($product['id']); ?>"
                                data-price="<?php echo htmlspecialchars($product['price']); ?>"
                                data-name="<?php echo htmlspecialchars($product['name'], ENT_QUOTES); ?>">
                            <?php echo htmlspecialchars($product['name']); ?> ($<?php echo number_format($product['price'], 2); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <div id="<?php echo strtolower($category); ?>-summary" class="mt-2 text-gray-600 text-sm italic"></div>
            </div>
        <?php endforeach; ?>

        <div class="border-t border-gray-200 pt-6 mt-6">
            <div class="flex justify-between items-center mb-4 text-xl font-bold text-gray-800">
                <span>Estimated Total Price:</span>
                <span>$<span id="estimated-total-price">0.00</span></span>
            </div>

            <button type="submit"
                    class="w-full flex justify-center py-3 px-6 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-[--color-dark-blue] hover:bg-[#1a2d3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[--color-primary-orange] transition-colors">
                Get Build Rating
            </button>
        </div>
    </form>

    <div id="build-rating-results" class="mt-10 p-6 bg-blue-50 rounded-lg border border-blue-200 shadow-md hidden">
        <h2 class="text-3xl font-extrabold text-[--color-dark-blue] mb-4 text-center">Your Build Rating: <span id="rating-score" class="text-[--color-primary-orange]">--</span>%</h2>
        <p id="qualitative-rating" class="text-center text-[--color-dark-blue] text-lg font-semibold mb-6"></p>

        <h3 class="text-xl font-bold text-gray-800 mb-2">Comments & Recommendations:</h3>
        <ul id="rating-comments" class="list-disc list-inside text-gray-700 space-y-1">
            </ul>
        <p class="text-center text-gray-500 text-sm mt-6">This rating is a general guide and based on simplified logic. For detailed compatibility, always cross-reference component specifications.</p>
    </div>
</div>

<script>
    // Map of selected component IDs and their details
    const selectedBuildComponents = {};

    // Update the estimated total price and selected component summaries
    function updateBuildSummary() {
        let total = 0;
        document.querySelectorAll('#build-form select').forEach(selectElement => {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price || 0);
            const name = selectedOption.dataset.name || '';
            const category = selectElement.id; // e.g., 'cpu', 'gpu'

            const summaryDiv = document.getElementById(`${category}-summary`);
            if (selectedOption.value) {
                total += price;
                summaryDiv.textContent = `Selected: ${name} ($${price.toFixed(2)})`;
                selectedBuildComponents[category] = selectedOption.value; // Store ID
            } else {
                summaryDiv.textContent = '';
                delete selectedBuildComponents[category]; // Remove from selected
            }
        });
        document.getElementById('estimated-total-price').textContent = total.toFixed(2);
    }

    // Handle form submission to get the rating
    document.getElementById('build-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const sendButton = e.submitter; // The button that was clicked
        sendButton.disabled = true;
        sendButton.textContent = 'Getting Rating...';
        sendButton.classList.add('opacity-50', 'cursor-not-allowed');

        const ratingResultsDiv = document.getElementById('build-rating-results');
        const ratingScoreSpan = document.getElementById('rating-score');
        const qualitativeRatingP = document.getElementById('qualitative-rating');
        const ratingCommentsUl = document.getElementById('rating-comments');

        // Reset previous results
        ratingResultsDiv.classList.add('hidden');
        ratingCommentsUl.innerHTML = '';
        ratingScoreSpan.textContent = '--';
        qualitativeRatingP.textContent = '';

        // Prepare data for the API call
        const formData = new FormData();
        Object.values(selectedBuildComponents).forEach(id => {
            formData.append('components[]', id); // Append each selected ID
        });

        try {
            const response = await fetch('/pcbuild/public/build-rate/get', {
                method: 'POST',
                body: formData, // Use FormData for traditional POST data
            });

            const data = await response.json();

            if (response.ok && data.success) {
                ratingScoreSpan.textContent = data.rating;
                qualitativeRatingP.textContent = data.qualitative_rating;

                if (data.comments.length > 0) {
                    data.comments.forEach(comment => {
                        const li = document.createElement('li');
                        li.textContent = comment;
                        ratingCommentsUl.appendChild(li);
                    });
                } else {
                    const li = document.createElement('li');
                    li.textContent = "No specific comments or recommendations for this build.";
                    ratingCommentsUl.appendChild(li);
                }

                ratingResultsDiv.classList.remove('hidden');
            } else {
                alertMessage('error', data.message || 'Failed to get build rating.');
                console.error('Build Rating Error:', data.error || data.message);
            }
        } catch (error) {
            alertMessage('error', 'Could not connect to the rating service.');
            console.error('Fetch error:', error);
        } finally {
            sendButton.disabled = false;
            sendButton.textContent = 'Get Build Rating';
            sendButton.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    });

    // Initial update when page loads
    document.addEventListener('DOMContentLoaded', updateBuildSummary);
</script>