<header class="top-header">
    <nav class="navbar navbar-expand">
        <div class="sidebar-header">
            <div class="d-none d-lg-flex">
                <img src="/admin/assets/images/logo-icon.png" class="logo-icon-2" alt="">
            </div>
            <div>
                <h4 class="d-none d-lg-flex logo-text">Syndash</h4>
            </div>
            <a href="javascript:;" class="toggle-btn ms-lg-auto"> <i class="bx bx-menu"></i>
            </a>
        </div>
        <div class="flex-grow-1 search-bar">
            <div class="input-group">
                {{-- <button class="btn btn-search-back search-arrow-back" type="button"><i
                        class="bx bx-arrow-back"></i></button>
                <input type="text" class="form-control" placeholder="search" />
                <button class="btn btn-search" type="button"><i class="lni lni-search-alt"></i></button> --}}
            </div>
        </div>
        <div class="right-topbar ms-auto">
            <ul class="navbar-nav">
                {{-- <li class="nav-item search-btn-mobile">
                    <a class="nav-link position-relative" href="javascript:;"> <i
                            class="bx bx-search vertical-align-middle"></i>
                    </a>
                </li> --}}
               
                
                <li class="nav-item dropdown dropdown-user-profile">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                        data-bs-toggle="dropdown">
                        <div class="d-flex user-box align-items-center">
                            <div class="user-info">
                                <p class="user-name mb-0">{{ Auth::user()->username }}</p>
                                <p class="designattion mb-0">{{ Auth::user()->account->name }}</p>
                            </div>
                            <img src="@if (Auth::user()->profile_url){{ Storage::url(Auth::user()->profile_url) }} @else /admin/assets/images/avatars/avatar-1.png @endif " class="user-img" alt="user avatar">
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="{{ route('global.user_profile.view') }}"><i
                                class="bx bx-user"></i><span>Profil</span></a>
                        
                        <a class="dropdown-item" href="{{ route('global.users.view') }}"><i
                                class="bx bx-user"></i><span>Gestion du compte</span></a>
                        
                        <a class="dropdown-item" href="{{ route('global.logs.view') }}"><i
                                class="bx bx-tachometer"></i><span>Journal</span></a>
                       
                        <a class="dropdown-item" href="javascript:;"><i
                                class="bx bx-cloud-download"></i><span>Exportation</span></a>
                        <div class="dropdown-divider mb-0"></div> <a class="dropdown-item" href="{{ route('auth.logout') }}"><i
                                class="bx bx-power-off"></i><span>Déconnexion</span></a>
                    </div>
                </li>
                {{-- <li class="nav-item ">
                    <a class="nav-link " href="javascript:;"
                        data-bs-toggle="dropdown">
                        <div class="lang d-flex">
                            <div><i class="flag-icon flag-icon-um"></i>
                            </div>
                            <div><span>Démarrer</span>
                            </div>
                        </div>
                    </a>
                </li> --}}
            </ul>
        </div>
    </nav>
</header>
