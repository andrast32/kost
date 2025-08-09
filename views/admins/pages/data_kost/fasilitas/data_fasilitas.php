<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="d-flex align-items-center">

                        <h4 class="card-title">
                            <?= $h1; ?>
                        </h4>

                        <button class="btn btn-border btn-round btn-primary ms-auto" style="margin-right: 0.5rem;" data-bs-toggle="modal" data-bs-target="#add">
                            <i class="fas fa-plus"></i> Add <?= $p; ?>
                        </button>

                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="laporan" class="display table table-striped table-hover">

                            <thead>
                                <tr align="center">
                                    <th>No</th>
                                    <th>Nama Fasilitas</th>
                                    <th>Deskripsi</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr align="center">
                                    <th>No</th>
                                    <th>Nama Fasilitas</th>
                                    <th>Deskripsi</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </tfoot>

                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>