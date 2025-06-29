<div class="modal fade" id="addActionModal" tabindex="-1" aria-hidden="true">
    <form action="" method="post" class="modal-dialog modal-dialog-centered">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau plan d'action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div>
                    <input class="form-control mb-3" type="text" placeholder="Type d'action"
                        aria-label=".form-control-lg example" name="action_type">
                </div>
                <div>
                    <input class="form-control mb-3" type="text" placeholder="priorite"
                        aria-label=".form-control-lg example" name="priorite">
                </div>
                <div>
                    <input class="form-control mb-3" type="text" placeholder="responsable"
                        aria-label=".form-control-lg example" name="responsable">
                </div>
                <div>
                    <textarea name="description" placeholder="Description" class="form-control mb-3" id="description"></textarea>
                </div>
                <div>
                    <input class="form-control mb-3" type="datetime-local" placeholder="Date de début prévue"
                        aria-label=".form-control-lg example" name="date_debut_prevue">
                </div>
                <div>
                    <input class="form-control mb-3" type="datetime-local" placeholder="Date de fin prévue"
                        aria-label=".form-control-lg example" name="date_fin_prevue">
                </div>

                <div class="mb-3">

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </form>
</div>
