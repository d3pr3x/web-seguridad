<?php

namespace App\Observers;

use App\Services\AuditoriaService;
use Illuminate\Database\Eloquent\Model;

class AuditoriaObserver
{
    public function created(Model $model): void
    {
        AuditoriaService::desdeModelo($model, 'create', null, $model->getAttributes());
    }

    public function updated(Model $model): void
    {
        AuditoriaService::desdeModelo(
            $model,
            'update',
            $model->getOriginal(),
            $model->getAttributes()
        );
    }

    public function deleted(Model $model): void
    {
        $accion = $model->isForceDeleting() ? 'force_delete' : 'delete';
        AuditoriaService::registrar(
            $accion,
            $model->getTable(),
            $model->getKey(),
            $model->getOriginal(),
            null
        );
    }

    public function restored(Model $model): void
    {
        AuditoriaService::desdeModelo($model, 'restore', null, $model->getAttributes());
    }
}
