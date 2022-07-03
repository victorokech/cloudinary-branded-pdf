<div>
	{{-- Be like water. --}}
	@if (session()->has('message'))
		<div class="alert alert-success alert-dismissible fade show m-3" role="alert">
			<h4 class="alert-heading">Awesomeness!</h4>
			<p>{{ session('message') }}</p>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@elseif(session()->has('error'))
		<div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
			<h4 class="alert-heading">Oops!</h4>
			<p>{{ session('error') }}</p>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	@endif
	<div class="flex h-screen justify-center items-center">
		<div class="row w-75">
			<div class="row mt-4">
				@foreach($this->files as $media)
					@if ($media)
						<div class="col-sm-3 col-md-3 mb-3">
							<img class="card-img-top img-thumbnail img-fluid" src="{{ $media->temporaryUrl() }}" alt="Card image cap">
						</div>
					@endif
				@endforeach
			</div>
			<div class="col-md-12">
				<form class="mb-5" wire:submit.prevent="uploadImages">
					<div class="form-group row mt-5 mb-3">
						<div class="input-group mb-5">
							<input id="watermark" type="file" class="form-control @error('watermark') is-invalid @enderror"
							       placeholder="Choose files..." wire:model="watermark">
							<label class="input-group-text" for="media">
								Choose watermark...
							</label>
							@error('watermark')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						<div class="input-group mb-3">
							<span class="input-group-text" id="basic-addon1">#</span>
							<input class="form-control @error('tag') is-invalid @enderror" placeholder="Portfolio Tag"
							       aria-label="Portfolio Tag"
							       aria-describedby="basic-addon1" wire:model="tag">
							@error('tag')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						<div class="input-group">
							<input id="files" type="file" class="form-control @error('files'|'files.*') is-invalid @enderror"
							       placeholder="Choose files..." wire:model="files" multiple>
							<label class="input-group-text" for="files">
								Choose images for portfolio...
							</label>
							
							@error('files'|'files.*')
							<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>
						<small class="text-muted text-center mt-2" wire:loading wire:target="files">
							{{ __('Uploading') }}&hellip;
						</small>
						<small class="text-muted text-center mt-2" wire:loading wire:target="watermark">
							{{ __('Uploading') }}&hellip;
						</small>
					</div>
					<div class="text-center">
						<button type="submit" class="btn btn-sm btn-primary w-25">
							<i class="fas fa-check mr-1"></i> {{ __('Generate PDF') }}
							<i class="spinner-border spinner-border-sm ml-1 mt-1" wire:loading wire:target="uploadImages"></i>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
