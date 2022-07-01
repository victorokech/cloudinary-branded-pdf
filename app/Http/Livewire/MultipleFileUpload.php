<?php
	
	namespace App\Http\Livewire;
	
	use Cloudinary\Api\Upload\UploadApi;
	use Cloudinary\Api\UploadApiClient;
	use Livewire\Component;
	use Livewire\WithFileUploads;
	
	class MultipleFileUpload extends Component {
		use WithFileUploads;
		
		public $watermarkId = 'watermark';
		public $watermark;
		public $tag;
		public $files = [];
		
		
		public function uploadImages() {
			$data = $this->validate([
				'files'     => 'required|image|max:1024',
				'files.*'   => 'required|image|max:1024|mimes:jpeg,jpg,png',
				'watermark' => [
					'required',
					'image',
					'mimes:png',
					'max:100'
				],
				'tag'       => [
					'required',
					'string',
					'max:20'
				],
			]);
			
			$watermarkPublicId = cloudinary()->upload($data['watermark']->getRealPath(), [
				'folder'    => 'branded-pdf',
				'public_id' => $this->watermarkId,
				'transformation' => [
					'width' => '100',
					'height' => '100'
				]
			])->getPublicId();
			
			foreach ($this->files as $file) {
				cloudinary()->upload($file->getRealPath(), [
					'folder'         => 'branded-pdf',
					'width'          => '560',
					'height'         => '800',
					'gravity'        => 'auto',
					'crop'           => 'fill',
					'tags'           => ["$this->tag"],
				]);
			}
			
			$this->tag = $data['tag'];
			cloudinary()->uploadApi()->multi($this->tag, [
				'transformation' => [
					'overlay' => $watermarkPublicId,
					'gravity' => 'north_east', // watermark location bottom right
					'x'       => 0.02, // 2 percent offset horizontally
					'y'       => 0.02, // 2 percent offset vertically
					'crop'    => 'scale',
				],
				'format'           => 'pdf',
				'notification_url' => env('CLOUDINARY_NOTIFICATION_URL')
			]);
		}
		
		public function render() {
			return view('livewire.multiple-file-upload');
		}
	}
