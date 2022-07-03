<?php
	
	namespace App\Http\Livewire;
	
	use Livewire\Component;
	use Livewire\WithFileUploads;
	
	class MultipleFileUpload extends Component {
		use WithFileUploads;
		
		public $files = [];
		public $watermark;
		public $tag;
		
		
		public function uploadImages() {
			$this->validate([
				'files'     => [
					'required',
					'max:10240'
				],
				'files.*'   => 'mimes:jpeg,jpg,png',
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
			
			$watermarkPublicId = cloudinary()->upload($this->watermark->getRealPath(), [
				'folder'         => 'branded-pdf',
				'public_id'      => 'watermark',
			])->getPublicId();
			
			foreach ($this->files as $file) {
				cloudinary()->upload($file->getRealPath(), [
					'folder'  => 'branded-pdf',
					'width'   => '794',
					'height'  => '1123',
					'gravity' => 'auto',
					'crop'    => 'fill',
					'tags'    => ["$this->tag"],
				]);
			}
			
			cloudinary()->uploadApi()->multi($this->tag, [
				'transformation' => [
					'overlay' => $watermarkPublicId,
					'gravity' => 'north_east',
					'x'       => 0.02,
					'y'       => 0.02,
					'crop'    => 'scale',
					'flags'   => 'relative',
					'width'   => 0.15,
					'opacity' => 80
				],
				'format'           => 'pdf',
				'notification_url' => env('CLOUDINARY_NOTIFICATION_URL')
			]);
		}
		
		public function render() {
			return view('livewire.multiple-file-upload');
		}
	}
