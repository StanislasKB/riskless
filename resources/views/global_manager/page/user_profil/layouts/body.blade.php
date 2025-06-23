<div class="user-profile-page">
    <div class="card radius-15">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-7 border-right">
                    <div class="d-md-flex align-items-center">
                        <div class="mb-md-0 mb-3">
                            <img src="@if (Auth::user()->profile_url) {{ Storage::url(Auth::user()->profile_url) }}@else /admin/assets/images/avatars/avatar-1.png @endif "
                                class="rounded-circle shadow" width="130" height="130" alt="" />
                        </div>
                        <div class="ms-md-4 flex-grow-1">

                            <p class="mb-0 text-muted">{{ Auth::user()->username }}</p>
                            <p class="text-primary"><i class='bx bx-buildings'></i>
                                @switch (Auth::user()->roles->first()->name)
                                    @case ('admin')
                                        Admin
                                    @break

                                    @case ('service_user')
                                        Utilisateur Service
                                    @break

                                    @case ('viewer')
                                        Lecteur
                                    @break

                                    @case ('owner')
                                        Propriétaire
                                    @break

                                    @default
                                        Indéfini
                                    @break;
                                @endswitch
                            </p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#updateProfil">Changer la photo de profil</button>
                        </div>
                    </div>
                </div>
                @include('global_manager.page.user_profil.layouts.profile_image_modal')
            </div>
            <!--end row-->
            <ul class="nav nav-pills mt-4">
                <li class="nav-item"> <a class="nav-link active" data-bs-toggle="tab" href="#Edit-Profile"><span
                            class="p-tab-name">Profil</span><i class='bx bx-message-edit font-24 d-sm-none'></i></a>
                </li>
                <li class="nav-item"> <a class="nav-link " data-bs-toggle="tab" href="#Experience"><span
                            class="p-tab-name">Paramètres</span><i class='bx bx-donate-blood font-24 d-sm-none'></i></a>
                </li>
                {{-- <li class="nav-item"> <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#Biography"><span
                            class="p-tab-name">Biography</span><i
                            class='bx bxs-user-rectangle font-24 d-sm-none'></i></a>
                </li> --}}

            </ul>
            <div class="tab-content mt-3">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @error('error')
                    <div class="alert alert-danger" role="alert">{{ $message }}</div>
                @enderror
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger" role="alert">{{ $error }}</div>
                @endforeach
                <div class="tab-pane fade " id="Experience">
                    <div class="card shadow-none border mb-0 radius-15">
                        <div class="card-body">
                            <h5 class="mb-5">Notifications (mail)</h5>
                            <form action="{{ route('global.update_notifications.post') }}" method="post">
                                @csrf
                                @foreach ($notifications as $notif)
                                    <div class="row mb-1 col-4">
                                        <div class="col-11">
                                            <p class="">{{ $notif->label }}</p>
                                        </div>
                                        <div class="col-1">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                    id="flexSwitchCheckDefault"name="notifications[{{ $notif->code }}]"
                                                    value="1"
                                                    {{ Auth::user()->isNotificationEnabled($notif->code) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="flexSwitchCheckDefault"></label>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach


                            </form>

                            <div class="div">
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="tab-pane fade" id="Biography">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card shadow-none border mb-0">
                                <div class="card-body">
                                    <h5 class="">Websites</h5>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">
                                        <p class="mb-0"><i class='bx bx-globe me-1'></i> Website: <a
                                                href="javascript:;">svetlananyukova.com</a>
                                        </p>
                                    </li>
                                    <li class="list-group-item">
                                        <p class="mb-0"><i class='bx bxl-blogger me-1'></i> Blog: <a
                                                href="javascript:;">blog.svetlananyukova.com</a>
                                        </p>
                                    </li>
                                    <li class="list-group-item">
                                        <p class="mb-0"><i class='bx bx-images me-1'></i> Portfolio: <a
                                                href="javascript:;">svetlananyukova.com/portfolio</a>
                                        </p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card shadow-none border mb-0 radius-15">
                                <div class="card-body">
                                    <h5 class="mb-3">About</h5>
                                    <p>There are many variations of passages of Lorem Ipsum available, but the majority
                                        have suffered alteration in some form, by injected humour, or randomised words
                                        which don't look even slightly believable. If you are going to use a passage of
                                        Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the
                                        middle of text. All the Lorem Ipsum generators on the Internet tend to repeat
                                        predefined chunks as necessary, making this the first true generator on the
                                        Internet. It uses a dictionary of over 200 Latin words, combined with a handful
                                        of model sentence structures, to generate Lorem Ipsum which looks reasonable.
                                    </p>
                                    <hr>
                                    <h5 class="mb-3">Skills</h5>
                                    <div class="chip">UI Development <span class="closebtn"
                                            onclick="this.parentElement.style.display='none'">×</span>
                                    </div>
                                    <div class="chip">android <span class="closebtn"
                                            onclick="this.parentElement.style.display='none'">×</span>
                                    </div>
                                    <div class="chip">iOS <span class="closebtn"
                                            onclick="this.parentElement.style.display='none'">×</span>
                                    </div>
                                    <div class="chip">python <span class="closebtn"
                                            onclick="this.parentElement.style.display='none'">×</span>
                                    </div>
                                    <div class="chip">javascript <span class="closebtn"
                                            onclick="this.parentElement.style.display='none'">×</span>
                                    </div>
                                    <div class="chip">sketch <span class="closebtn"
                                            onclick="this.parentElement.style.display='none'">×</span>
                                    </div>
                                    <div class="chip">photoshop <span class="closebtn"
                                            onclick="this.parentElement.style.display='none'">×</span>
                                    </div>
                                    <div class="chip">Html5 <span class="closebtn"
                                            onclick="this.parentElement.style.display='none'">×</span>
                                    </div>
                                    <div class="chip">bootstrap4 <span class="closebtn"
                                            onclick="this.parentElement.style.display='none'">×</span>
                                    </div>
                                    <div class="chip">jQuery <span class="closebtn"
                                            onclick="this.parentElement.style.display='none'">×</span>
                                    </div>
                                    <div class="chip">php Development <span class="closebtn"
                                            onclick="this.parentElement.style.display='none'">×</span>
                                    </div>
                                    <div class="chip">ms excel <span class="closebtn"
                                            onclick="this.parentElement.style.display='none'">×</span>
                                    </div>
                                    <div class="chip">programming <span class="closebtn"
                                            onclick="this.parentElement.style.display='none'">×</span>
                                    </div>
                                    <h5 class="mb-3">Language</h5>
                                    <hr>
                                    <ul class="list-inline mb-0">
                                        <li class="list-inline-item"><i
                                                class="flag-icon flag-icon-um me-2"></i><span>English</span>
                                        </li>
                                        <li class="list-inline-item"><i
                                                class="flag-icon flag-icon-fr me-2"></i><span>French</span>
                                        </li>
                                        <li class="list-inline-item"><i
                                                class="flag-icon flag-icon-de me-2"></i><span>German</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="tab-pane fade show active" id="Edit-Profile">
                    <div class="card shadow-none border mb-0 radius-15">
                        <div class="card-body">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12 col-lg-5 border-right">
                                        <form class="row g-3" action="{{ route('global.update_username.post') }}"
                                            method="POST">
                                            @csrf
                                            <div class="col-12">
                                                <label class="form-label">Nom d'utilisateur</label>
                                                <input type="text" value="{{ Auth::user()->username }}"
                                                    class="form-control" name="username">
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">Email</label>
                                                <input type="text" value="{{ Auth::user()->email }}"
                                                    class="form-control" disabled>
                                            </div>
                                            <div>
                                                <button type="submit" class="btn btn-primary">Changer</button>
                                            </div>

                                        </form>
                                        <div class="mt-5">
                                            <a href="{{ route('global.email_request.view') }}" class="btn btn-primary">Change l'adresse email</a>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-7">
                                        <form class="row g-3" method="POST"
                                            action="{{ route('global.update_password.post') }}">
                                            @csrf
                                            <div class="col-7">
                                                <div class="col-12 mb-2">
                                                    <label class="form-label">Ancien mot de passe</label>
                                                    <input type="password" class="form-control" name="old_password">
                                                </div>
                                                <div class="col-12 mb-2">
                                                    <label class="form-label">Nouveau mot de passe</label>
                                                    <input type="password" class="form-control" name="new_password">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Confirmation nouveau mot de passe</label>
                                                    <input type="password" class="form-control" name="new_password2">
                                                </div>
                                                <div class="mt-3">
                                                    <button type="submit" class="btn btn-primary">Changer mot de
                                                        passe</button>
                                                </div>
                                            </div>


                                            <div class="col-5">
                                                <div class="col-12">
                                                    <h6>
                                                        Le nouveau mot de passe doit contenir :
                                                    </h6>
                                                    <ul class="list-group list-group-flush">
                                                        <li class="list-group-item">
                                                            <i class=" text-success f-16 me-2"></i>
                                                            Au moins 8 caractères
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="ti ti-circle-check text-success f-16 me-2"></i>
                                                            Au moins 1 lettre minuscule
                                                            (a-z)
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="ti ti-circle-check text-success f-16 me-2"></i>
                                                            Au moins 1 lettre majuscule
                                                            (A-Z)
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="ti ti-circle-check text-success f-16 me-2"></i>
                                                            Au moins 1 chiffre (0-9)
                                                        </li>
                                                        <li class="list-group-item">
                                                            <i class="ti ti-circle-check text-success f-16 me-2"></i>
                                                            Au moins 1 caractère spécial
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>



                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
