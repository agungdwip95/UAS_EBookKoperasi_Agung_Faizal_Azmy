<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angsuran extends Model
{
	protected $table = "angsuran";
	protected $fillable = array('tgl_pembayaran', 'angsuran_ke', 'besar_angsuran', 'anggota_id');

	// untuk melakukan update field created_at dan updated_at secara otomatis
	public $timestamps = true;

	public function anggota()
	{
		return $this->belongsTo('App\Models\Anggota');
	}

	public function pinjaman(){
		return $this->hasMany('App\Models\Pinjaman');
	}
}

?>

