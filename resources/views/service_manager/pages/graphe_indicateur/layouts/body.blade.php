<h5 class="mb-0">Evolution pour {{ $indicateur->libelle }}</h5>
					<hr/>
<div class="row">
    <div class="col-12 col-lg-12">
        <div class="card radius-15">
            <div class="card-body">
                <div id="chart-indicateur"
                    data-graphe="{{ $data }}">
                </div>
            </div>
        </div>
    </div>
</div>
