<nav class="flex items-center bg-gray-500 p-3 flex-wrap rounded-lg mb-4">
    <button
        class="text-white inline-flex p-3 hover:bg-gray-500 rounded lg:hidden ml-auto hover:text-white outline-none nav-toggler border-white"
        data-target="#navigation"
    >
        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
    </button>
    <div
        class="hidden top-navbar w-full lg:inline-flex lg:flex-grow lg:w-auto"
        id="navigation"
    >
        <div class="lg:inline-flex lg:flex-row lg:ml-auto lg:w-auto w-full lg:items-center items-start  flex flex-col lg:h-auto">
            <a
                href="/"
                class="lg:inline-flex lg:w-auto w-full px-3 py-2 rounded text-white items-center justify-center hover:bg-gray-600 hover:text-white"
            >
                <span>Leaderboard</span>
            </a>
            <a
                href="/chart"
                class="lg:inline-flex lg:w-auto w-full px-3 py-2 rounded text-white items-center justify-center hover:bg-gray-600 hover:text-white"
            >
                <span>Chart</span>
            </a>
        </div>
    </div>
</nav>
