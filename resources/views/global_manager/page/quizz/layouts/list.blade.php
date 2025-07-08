<div class="d-flex justify-content-between">
  <a href="{{ route('global.quizz.add.view') }}" class="btn btn-primary">Ajouter un quizz</a>
  <a href="" class="btn btn-primary">Liste soumissions</a>
					
</div>
<hr>
<div class="row">
     @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif


            @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">{{ $error }}</div>
            @endforeach
     @forelse ($quizzs as $quizz)
         <div class="col-12 col-lg-4 col-xl-4">
        <div class="card">
            <img src="@if ($quizz->img_url!=null){{ Storage::url($quizz->img_url) }} @else /admin/assets/images/gallery/14.jpg @endif " class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title">{{ $quizz->title }}</h5>
                <p class="card-text">{{ $quizz->description }}</p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div class="fw-bold">Status</div>
                        <div>@switch($quizz->status)
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
                        <div class="fw-bold">Ajouté par</div>
                        <div>{{ $quizz->creator->username }}</div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <div class="fw-bold">Visibilité</div>
                        <div>@switch($quizz->visibility)
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
                        <div class="fw-bold"><a href="{{ route('global.quizz.update.view',['id'=>$quizz->id]) }}" class="card-link">Modifier</a>
									</div>
                        <div class="fw-bold"><a href="{{ route('global.quizz.delete',['id'=>$quizz->id]) }}" class="card-link">Supprimer</a></div>
                    </div>
                </li>
                
            </ul>
            <div class="card-body d-flex justify-content-between">
              <a href="{{ Storage::url($quizz->document_url) }}" class="btn btn-primary">Télécharger</a>
              @if (!Auth::user()->quizzResponses()->exists())
              <a href="#"  data-bs-toggle="modal" data-bs-target="#submitResponse-{{ $quizz->id }}" class="btn btn-primary">Soumettre une réponse</a>
              @endif
            </div>
            @include('global_manager.page.quizz.layouts.response.modal')
        </div>
    </div>
     @empty
         <h5>Aucun quizz disponible</h5>
     @endforelse
    
</div>
@php
    $pagination_list=$quizzs;
@endphp
<div class="d-flex align-items-center justify-content-center">
    @include('global_manager.components.layouts.pagination')
</div>