<div class="row">
     @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif


            @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">{{ $error }}</div>
            @endforeach
     @forelse ($formations as $formation)
         <div class="col-12 col-lg-4 col-xl-4">
        <div class="card">
            <img src="@if ($formation->img_url!=null){{ Storage::url($formation->img_url) }} @else /admin/assets/images/gallery/14.jpg @endif " class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">{{ $formation->title }}</h5>
                <p class="card-text">{{ $formation->description }}</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div class="fw-bold">Status</div>
                        <div>@switch($formation->status)
                            @case('ACTIVE')
                                <span class="badge bg-success">Actif</span>
                                @break
                            @case('INACTIVE')
                                <span class="badge bg-danger">Inactif</span>
                                @break
                        
                            @default
                                
                        @endswitch</div>
                    </div>

                </li>
                  <li class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div class="fw-bold">Visibilité</div>
                        <div>@switch($formation->visibility)
                            @case('ALL')
                                    Tout le monde
                                @break
                        
                            @case('ONLY_MEMBERS')
                                    Collègues uniquement
                                @break
                            @default
                                
                        @endswitch</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div class="fw-bold">Ajouté par</div>
                        <div>{{ $formation->creator->username }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div class="fw-bold"><a href="{{ route('global.formation.update.view',['id'=>$formation->id]) }}" class="card-link">Modifier</a>
									</div>
                        <div class="fw-bold"><a href="{{ route('global.formation.delete',['id'=>$formation->id]) }}" class="card-link">Supprimer</a></div>
                    </div>
                </li>
                
            </ul>
            <div class="card-body">
              <a href="{{ Storage::url($formation->document_url) }}" class="btn btn-primary">Télécharger</a>
            </div>
        </div>
    </div>
     @empty
         <h5>Aucune formation disponible</h5>
     @endforelse
    
</div>
@php
    $pagination_list=$formations;
@endphp
<div class="d-flex align-items-center justify-content-center">
    @include('global_manager.components.layouts.pagination')
</div>