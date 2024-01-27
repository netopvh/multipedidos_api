<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[OA\Schema(
    title: "Car",
    description: "Modelo de um carro",
    required: ["model", "brand", "color", "year"]
)]
class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $fillable = [
        'model',
        'brand',
        'color',
        'year',
    ];
}
