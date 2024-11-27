<div class="fixed bottom-5 right-5 z-50 space-y-4">
    @if (session('success'))
        <div id="alert-success" class="alert bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-md opacity-100 transition-opacity duration-500 flex items-center justify-between" role="alert">
            <button type="button" class="px-4 py-2 text-green-700 hover:text-green-800" onclick="this.parentElement.remove()">
                &times;
            </button>
        </div>
    @endif

    @if (session('error'))
        <div id="alert-error" class="alert bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded shadow-md opacity-100 transition-opacity duration-500 flex items-center justify-between" role="alert">
            <button type="button" class="px-4 py-2 text-red-700 hover:text-red-800" onclick="this.parentElement.remove()">
                &times;
            </button>
        </div>
    @endif
</div>
