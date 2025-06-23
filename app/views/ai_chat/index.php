<div id="chat-messages" class="flex-grow overflow-y-auto bg-[--color-light-bg] p-4 rounded-lg shadow-inner space-y-4">
        </div>

    <form id="chat-input-form" class="flex-shrink-0 flex items-center space-x-4 p-4 border-t border-gray-200 bg-white">
        <input type="text" id="user-input" placeholder="Type your message..."
               class="flex-grow px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-[--color-primary-orange] focus:border-[--color-primary-orange] text-gray-700">
        <button type="submit" id="send-button"
                class="bg-[--color-dark-blue] hover:bg-[#1a2d3a] text-white font-bold py-3 px-6 rounded-md shadow-lg transition-colors flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
            <span class="ml-2 hidden sm:inline">Send</span>
        </button>
    </form>