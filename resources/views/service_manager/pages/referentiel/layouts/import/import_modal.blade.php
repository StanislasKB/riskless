
<button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#importFicheModal">Importer</button>

<div class="modal fade" id="importFicheModal" tabindex="-1" aria-hidden="true">
    <form action="{{ route('service.import_fiche_risque.post',['uuid'=>$service->uuid]) }}" method="POST" class="modal-dialog modal-dialog-centered modal-lg" enctype="multipart/form-data" >
        @csrf
        <div class="modal-content shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">Importer des fiches risque</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Fichier</label>
                    <input type="file" class="form-control" name="file" >
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="submit" class="btn btn-success">Importer</button>
            </div>
        </div>
    </form>
</div>
