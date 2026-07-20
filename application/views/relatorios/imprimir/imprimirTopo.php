<?php if ($emitente[0]): ?>
    <div>
        <br>
        <div style="width: 40%; float: left" class="float-left col-md-3">
            <img style="width: 150px" src="<?= convertUrlToUploadsPath($emitente[0]->url_logo) ?>" alt=""><br><br>
        </div>
        <div style="float: right">
            <?= $emitente[0]->nome ?><br>
            <?= $emitente[0]->rua ?>, <?= $emitente[0]->numero ?>, <?= $emitente[0]->bairro ?>, <?= $emitente[0]->cidade ?> - <?= $emitente[0]->uf ?> <br><hr>

            <?php if (isset($dataInicial)): ?>
                <b>DATA INICIAL: </b> <?= $dataInicial ?>
            <?php endif ?>

            <?php if (isset($dataFinal)): ?>
                <b>DATA FINAL: </b> <?= $dataFinal ?>
            <?php endif ?>
        </div>
    </div>
<?php endif ?>
