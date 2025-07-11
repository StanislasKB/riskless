<div class="nav-container">
    <div class="mobile-topbar-header">
        <div class="">
            <img src="assets/images/logo-icon.png" class="logo-icon-2" alt="" />
        </div>
        <div>
            <h4 class="logo-text">Syndash</h4>
        </div>
        <a href="javascript:;" class="toggle-btn ms-auto"> <i class="bx bx-menu"></i>
        </a>
    </div>
    <nav class="topbar-nav">
        <ul class="metismenu" id="menu">
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon icon-color-1"><i class="bx bx-home-alt"></i>
                    </div>
                    <div class="menu-title">Tableau de bord</div>
                </a>
                <ul>
                    <li> <a href="index.html"><i class="bx bx-right-arrow-alt"></i>Analytics</a>
                    </li>
                    <li> <a href="index2.html"><i class="bx bx-right-arrow-alt"></i>Sales</a>
                    </li>
                </ul>
            </li>
              <li>
                <a class="has-arrow" href="{{ route('global.service.view') }}">
                    <div class="parent-icon icon-color-3"><i class="bx bx-line-chart"></i>
                    </div>
                    <div class="menu-title">Services</div>
                </a>
              
            </li>
            <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon icon-color-2"><i class='bx bx-briefcase-alt'></i>
                    </div>
                    <div class="menu-title">Cartographie risques</div>
                </a>
                <ul>
                    <li> <a href="{{ route('global.referentiel.view') }}"><i class="bx bx-right-arrow-alt"></i>Référentiel</a>
                    </li>
                    <li> <a href="{{ route('global.matrice.view') }}"><i class="bx bx-right-arrow-alt"></i>Matrice des risques</a>
                    </li>
                    
                </ul>
            </li>
          
             <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon icon-color-5"><i class="bx bx-lock"></i>
                    </div>
                    <div class="menu-title">Formations</div>
                </a>
                <ul>
                    <li> <a href="{{ route('global.formation.list.view') }}"><i
                                class="bx bx-right-arrow-alt"></i>Formation</a>
                    </li>
                    <li> <a href="{{ route('global.quizz.list.view') }}"><i
                                class="bx bx-right-arrow-alt"></i>Quizz</a>
                    </li>
                   
                </ul>
            </li>
            
           
            <li>
                <a class="has-arrow" href="{{ route('global.configurations.view') }}">
                    <div class="parent-icon icon-color-6"> <i class="bx bx-donate-blood"></i>
                    </div>
                    <div class="menu-title">Configurations</div>
                </a>
                {{-- <ul>
                    <li> <a href="user-profile.html"><i class="bx bx-right-arrow-alt"></i>User Profile</a>
                    </li>
                    <li> <a href="timeline.html"><i class="bx bx-right-arrow-alt"></i>Timeline</a>
                    </li>
                    <li> <a href="pricing-table.html"><i class="bx bx-right-arrow-alt"></i>Pricing</a>
                    </li>
                    <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Errors</a>
                        <ul>
                            <li> <a href="errors-404-error.html"><i class="bx bx-right-arrow-alt"></i>404
                                    Error</a>
                            </li>
                            <li> <a href="errors-500-error.html"><i class="bx bx-right-arrow-alt"></i>500
                                    Error</a>
                            </li>
                            <li> <a href="errors-coming-soon.html"><i class="bx bx-right-arrow-alt"></i>Coming
                                    Soon</a>
                            </li>
                        </ul>
                    </li>
                </ul> --}}
            </li>
            <li>
                <a class="has-arrow" href="{{ route('global.processus.view') }}">
                    <div class="parent-icon icon-color-7"> <i class="bx bx-comment-edit"></i>
                    </div>
                    <div class="menu-title">Processus</div>
                </a>
                
            </li>
        </ul>
    </nav>
</div>
