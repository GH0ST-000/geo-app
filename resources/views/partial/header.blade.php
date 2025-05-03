<nav class="navbar top-navbar">
    <div class="container">
        <div class="navbar-content">
            <img style="width: 55px ; height: 55px" class="p-2" src="https://geogapp.site/assets/Logo-DSp0wGmi.svg" alt="">
            <form class="search-form">
                <div class="input-group">
                    <div class="input-group-text">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </div>
                    <input type="text" class="form-control" id="navbarForm" placeholder="ძებნა">
                </div>
            </form>
            <ul class="navbar-nav">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="wd-30 ht-30 rounded-circle" src="https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png" alt="profile">
                    </a>
                    <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                        <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                            <div class="mb-3">
                                <img class="wd-80 ht-80 rounded-circle" src="https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png" alt="">
                            </div>
                            <div class="text-center">
                                <p class="tx-16 fw-bolder">{{\Illuminate\Support\Facades\Auth::user()->first_name . ' ' .\Illuminate\Support\Facades\Auth::user()->last_name}}</p>
                                <p class="tx-12 text-muted">{{\Illuminate\Support\Facades\Auth::user()->email}}</p>
                            </div>
                        </div>
                        <ul class="list-unstyled p-1">
                            <li class="dropdown-item py-2">
                                <a  class="text-body ms-0" style="cursor: pointer">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out me-2 icon-md"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>

                                        <x-dropdown-link class="" :href="route('logout')"
                                                         onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                            <span style="position: absolute !important;left: 24%!important;">გამოსვლა</span>
                                        </x-dropdown-link>

                                    </form>

                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </button>
        </div>
    </div>
</nav>
