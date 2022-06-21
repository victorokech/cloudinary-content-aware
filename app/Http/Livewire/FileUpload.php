<?php
	
	namespace App\Http\Livewire;
	
	use Illuminate\Http\UploadedFile;
	use Illuminate\Support\Str;
	use Livewire\Component;
	use Livewire\WithFileUploads;
	
	class FileUpload extends Component {
		use WithFileUploads;
		
		public $media;
		public $croppedImages = [];
		
		public function mount() {
			$this->croppedImages = [
				"1:1" => "https://res.cloudinary.com/dgrpkngjn/image/upload/v1655798963/content-aware/xcstzkbkotraibhzix8n.jpg",
				"2:1" => "https://res.cloudinary.com/dgrpkngjn/image/upload/v1655798966/content-aware/qmgg54ojv4vkqyvn5d4k.jpg",
				"16:10" => "https://res.cloudinary.com/dgrpkngjn/image/upload/v1655798970/content-aware/uk1pfcfoyxetziggnac0.jpg"
			];
		}
		
		public function upload() {
			$data = $this->validate([
				'media' => [
					'required',
					'image',
					'mimes:jpeg,jpg,png',
				],
			]);
			
			/** @var UploadedFile $media */
			if (empty($data['media'])) {
				unset($data['media']);
			} else {
				$media = $data['media'];
				//$name = Str::random(15).'.'.$media->guessExtension();
				$aspect_ratio = ['1:1', '2:1', '16:10'];
				foreach ($aspect_ratio as $ac) {
					$image = cloudinary()->upload($media->getRealPath(), [
						'folder'         => 'content-aware',
						'transformation' => [
							'aspect_ratio' => $ac,
							'gravity'      => 'auto',
							'crop'         => 'fill'
						]
					])->getSecurePath();
					
					$this->croppedImages[$ac] = $image;
				}
			}
			
			session()->flash('message', 'Media file cropped successfully!');
		}
		
		public function render() {
			return view('livewire.file-upload');
		}
	}
