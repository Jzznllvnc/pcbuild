<div class="container mx-auto p-4 md:p-8 mt-24 mb-12 max-w-7xl">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-6 mt-8 text-center"><?php echo htmlspecialchars($title); ?></h1>

    <p class="text-center text-gray-700 mb-12 text-lg">Select your desired PC components below and click "Get Build Rating" to see how well they perform together!</p>

    <form id="build-form">
        <?php
        $componentIcons = [
            'CPU' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><path d="M15 2v2m-6 0V2m0 20v-2m6 0v2M2 9h2m20 0h-2M2 15h2m20 0h-2"></path></svg>',
            'GPU' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-hard-drive"><line x1="22" y1="12" x2="2" y2="12"></line><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path><line x1="6" y1="16" x2="6.01" y2="16"></line><line x1="10" y1="16" x2="10.01" y2="16"></line></svg>',
            'Motherboard' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>',
            'RAM' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-server"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>',
            'Storage' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-database"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M2 12A9 3 0 0 0 11 15V20A9 3 0 0 1 2 17Z"></path><path d="M22 12A9 3 0 0 1 13 15V20A9 3 0 0 0 22 17Z"></path></svg>',
            'PSU' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-zap"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>',
            'Case' => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>',
        ];
        ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($components as $category => $products): ?>
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm hover:shadow-lg transition-all duration-200 component-card">
                    <label for="<?php echo strtolower($category); ?>" class="flex items-center text-xl font-bold text-gray-800 mb-4">
                        <span class="text-[--color-primary-orange] mr-3"><?php echo $componentIcons[$category]; ?></span>
                        <?php echo htmlspecialchars($category); ?>:
                    </label>
                    <div class="relative">
                        <select id="<?php echo strtolower($category); ?>" name="<?php echo strtolower($category); ?>"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] sm:text-base bg-white appearance-none pr-8 transition-all duration-200"
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
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 6.757 7.586 5.343 9l4.95 4.95z"/></svg>
                        </div>
                    </div>
                    <div id="<?php echo strtolower($category); ?>-summary" class="mt-3 text-gray-600 text-sm italic"></div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="border-t border-gray-200 pt-8 mt-8">
            <div class="flex justify-between items-center mb-6 text-2xl font-bold text-gray-800">
                <span>Estimated Total Price:</span>
                <span>$<span id="estimated-total-price">0.00</span></span>
            </div>

            <div class="flex flex-col sm:flex-row justify-end space-y-4 sm:space-y-0 sm:space-x-4">
                <button type="button" onclick="clearBuildSelections()"
                        class="w-full sm:w-auto flex justify-center py-4 px-6 border border-transparent rounded-lg shadow-lg text-xl font-bold text-gray-800 bg-gray-300 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                    Clear Build
                </button>
                <button type="submit"
                        class="w-full sm:w-auto flex justify-center py-4 px-6 border border-transparent rounded-lg shadow-lg text-xl font-bold text-white bg-[--color-dark-blue] hover:bg-[#1a2d3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[--color-primary-orange] transition-colors">
                    Get Build Rating
                </button>
            </div>
        </div>
    </form>

    <div id="build-rating-results" class="mt-12 p-8 bg-blue-50 rounded-lg border border-blue-200 shadow-md hidden">
        <h2 class="text-3xl font-extrabold text-[--color-dark-blue] mb-4 text-center">Your Build Rating: <span id="rating-score" class="text-[--color-primary-orange]">--</span>%</h2>
        <p id="qualitative-rating" class="text-center text-[--color-dark-blue] text-xl font-semibold mb-6"></p>

        <h3 class="text-xl font-bold text-gray-800 mb-3">Comments & Recommendations:</h3>
        <p id="rating-comments" class="text-gray-700 text-base mt-2"></p>
        <p class="text-center text-gray-500 text-sm mt-8">This rating is a general guide and based on simplified logic. For detailed compatibility, always cross-reference component specifications.</p>
    </div>
</div>

<script>
    const selectedBuildComponents = {};

    function updateBuildSummary() {
        let total = 0;
        document.querySelectorAll('#build-form select').forEach(selectElement => {
            const selectedOption = selectElement.options[selectElement.selectedIndex];
            const price = parseFloat(selectedOption.dataset.price || 0);
            const name = selectedOption.dataset.name || '';
            const category = selectElement.id;

            const summaryDiv = document.getElementById(`${category}-summary`);
            if (selectedOption.value) {
                total += price;
                summaryDiv.textContent = `Selected: ${name} ($${price.toFixed(2)})`;
                selectedBuildComponents[category] = selectedOption.value;
            } else {
                summaryDiv.textContent = '';
                delete selectedBuildComponents[category];
            }
        });
        document.getElementById('estimated-total-price').textContent = total.toFixed(2);
    }

    // Clears all selected components in the build form
    function clearBuildSelections() {
        document.querySelectorAll('#build-form select').forEach(selectElement => {
            selectElement.value = '';
        });

        for (const key in selectedBuildComponents) {
            delete selectedBuildComponents[key];
        }
        updateBuildSummary();
        document.getElementById('build-rating-results').classList.add('hidden');
        alertMessage('success', 'Build selections cleared!');
    }

    // Handle form submission to get the rating
    document.getElementById('build-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const sendButton = e.submitter;
        sendButton.disabled = true;
        sendButton.textContent = 'Getting Rating...';
        sendButton.classList.add('opacity-50', 'cursor-not-allowed');

        const ratingResultsDiv = document.getElementById('build-rating-results');
        const ratingScoreSpan = document.getElementById('rating-score');
        const qualitativeRatingP = document.getElementById('qualitative-rating');
        const ratingCommentsUl = document.getElementById('rating-comments');

        ratingResultsDiv.classList.add('hidden');
        ratingCommentsUl.innerHTML = '';
        ratingScoreSpan.textContent = '--';
        qualitativeRatingP.textContent = '';

        if (Object.keys(selectedBuildComponents).length === 0) {
            alertMessage('error', 'Please select at least one component to get a rating.');
            sendButton.disabled = false;
            sendButton.textContent = 'Get Build Rating';
            sendButton.classList.remove('opacity-50', 'cursor-not-allowed');
            return;
        }
        const formData = new FormData();
        Object.values(selectedBuildComponents).forEach(id => {
            formData.append('components[]', id);
        });

        try {
            const response = await fetch('/build-rate/get', {
                method: 'POST',
                body: formData,
            });

            const data = await response.json();

            if (response.ok && data.success) {
                ratingScoreSpan.textContent = data.rating;
                qualitativeRatingP.textContent = data.qualitative_rating;

            if (data.comments.length > 0) {
                ratingCommentsUl.textContent = data.comments.join(' ');
            } else {
                ratingCommentsUl.textContent = "No specific comments or recommendations for this build.";
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
    document.addEventListener('DOMContentLoaded', updateBuildSummary);
</script>

<style>
    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: none;
    }
    .relative select + .pointer-events-none svg {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
    }
    .component-card {
        border: 2px solid var(--color-light-bg);
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .component-card:focus-within {
        border-color: var(--color-primary-orange);
        box-shadow: 0 0 0 3px rgba(254, 119, 67, 0.3);
    }
</style>