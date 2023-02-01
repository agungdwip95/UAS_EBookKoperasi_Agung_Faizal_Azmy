<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
	protected $table = "simpanan";
	protected $fillable = array('nama_simpanan', 'tgl_simpanan', 'besar_simpanan', 'anggota_id');

	// untuk melakukan update field created_at dan updated_at secara otomatis
	public $timestamps = true;

	public function anggota()
	{
		return $this->belongsTo('App\Models\Anggota');
	}
}

?>

