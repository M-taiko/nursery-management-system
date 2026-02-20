<?php

namespace App\Providers;

use App\Models\BehaviorRecord;
use App\Models\Child;
use App\Models\ChildPhoto;
use App\Models\DailyEvaluation;
use App\Models\FeeInvoice;
use App\Policies\BehaviorRecordPolicy;
use App\Policies\ChildPhotoPolicy;
use App\Policies\ChildPolicy;
use App\Policies\DailyEvaluationPolicy;
use App\Policies\FeeInvoicePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Child::class => ChildPolicy::class,
        DailyEvaluation::class => DailyEvaluationPolicy::class,
        ChildPhoto::class => ChildPhotoPolicy::class,
        FeeInvoice::class => FeeInvoicePolicy::class,
        BehaviorRecord::class => BehaviorRecordPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
