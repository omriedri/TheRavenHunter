<header>
    <div class="cover-wraaper position-fixed start-0 top-0 end-0 w-100">
        <div class="cover d-flex justify-content-center align-items-center">
            <img class="cover" src="public/images/cover.png" alt="Logo">
        </div>
    </div>

    <!-- <h1 class="title text-center my-2">The Bird Catcher</h1> -->
    <nav class="responsive-nav">
        <button class="trigger-button" aria-label="Open site navigation">
            <svg class="bi bi-list" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M2.5 11.5A.5.5 0 013 11h10a.5.5 0 010 1H3a.5.5 0 01-.5-.5zm0-4A.5.5 0 013 7h10a.5.5 0 010 1H3a.5.5 0 01-.5-.5zm0-4A.5.5 0 013 3h10a.5.5 0 010 1H3a.5.5 0 01-.5-.5z"
                    clip-rule="evenodd" />
            </svg>
        </button>
        <div class="nav-container nav-container position-fixed text-white top-0 bottom-0 px-0 py-4">
            <h2 class="mx-3">Navigation</h2>
            <ul class="menu-item-list mt-4 fs-4 ps-0">
                <li class="menu-item px-2 py-1">
                    <button class="w-100 text-white border-0 text-start bg-transparent" data-target="home">
                        <i class="bi bi-house-fill"></i>
                        <span>Home</span>
                    </button>
                </li>
                <!-- <li class="menu-item game px-2 py-1" data-user-logged>
                    <button class="w-100 text-white border-0 text-start bg-transparent" data-target="game">
                        <i class="bi bi-controller"></i>
                        <span>Play</span>
                    </button>
                </li> -->
                <li class="menu-item px-2 py-1" data-user-logged>
                    <button class="w-100 text-white border-0 text-start bg-transparent" data-target="records">
                        <i class="bi bi-house-fill"></i>
                        <span>Records</span> </button>
                </li>
                <li class="menu-item px-2 py-1" data-user-logged>
                    <button class="w-100 text-white border-0 text-start bg-transparent" data-target="settings">
                        <i class="bi bi-gear-fill"></i>
                        <span>Settings</span>
                    </button>
                </li>
                <li class="menu-item px-2 py-1">
                    <button class="w-100 text-white border-0 text-start bg-transparent" data-target="about">
                        <i class="bi bi-info-circle-fill"></i>
                        <span>About</span>
                    </button>
                </li>
                <li class="menu-item px-2 py-1" data-user-logged data-action="logout">
                    <button class="w-100 text-white border-0 text-start bg-transparent" data-target="logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </li>
            </ul>
        </div>
    </nav>

    <profile class="d-none">
        <div class="dropdown">
            <button class="user-avatar-wrap user-avatar-wrap border border-dark rounded-circle p-0 d-inline-block bg-white position-relative" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <img class="user-profile-image border rounded-circle position-relative start-0" width="35" height="35" src="" alt="" data-user-image>
            </button>
            <span class="user-nickname" data-user-name></span>
            <div class="dropdown-menu" aria-label="User options">
                <a class="dropdown-item profile" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                    <i class="bi bi-person-circle"></i>
                    <span>Profile</span>
                </a>
                <a class="dropdown-item logout" href="#" data-action="logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </profile>
</header>