<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Productionperson extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public const TYPE_SELECT = [
        'percetakan' => 'Percetakan',
        'finishing'  => 'Finishing',
    ];

    public $table = 'productionpeople';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'code',
        'name',
        'type',
        'contact',
        'alamat',
        'company',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachUser(User $user)
    {
        $roleTitle = ucfirst($this->type); // Percetakan / Finishing
        $role = Role::where('title', $roleTitle)->firstOrCreate([
            'title' => $roleTitle,
        ]);

        if (!$role->permissions()->count()) {
            $permissions = Permission::get()->filter(function($item) {
                return substr($item->title, 0, strlen('production_')) === 'production_';
            });
    
            $role->permissions()->sync($permissions->pluck('id'));
        }

        $user->roles()->sync($role->id);

        if ($user->id !== $this->user_id) {
            $this->update([ 'user_id' => $user->id ]);
        }
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            $number = Productionperson::withTrashed()->max('id') + 1;
            $model->code = 'PROD-'. str_pad($number, 6, '0', STR_PAD_LEFT);
        });
    }
}
