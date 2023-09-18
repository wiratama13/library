<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['isbn','title','year','publisher_id','author_id','catalog_id','qty','price'];
    
    public function publisher() {
        return $this->belongsTo(Publisher::class,'publisher_id');
    }

    public function author() {
        return $this->belongsTo(Author::class,'author_id');
    }

    public function catalog() {
        return $this->belongsTo(Catalog::class,'catalog_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'book_id', 'id');
    }

    public function booktoDetails()
    {
        return $this->belongsToMany(Transaction::class,'transaction_details','book_id', 'transaction_id');
    }
}
