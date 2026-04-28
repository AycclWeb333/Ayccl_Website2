@isset($page)
    <button type="button" class="btn btn-outline-warning btn-lg mb-10" data-toggle="modal" data-target="#categoryEdit">
        <i class="fas fa-edit"></i>
        {{ __('adminlte::adminlte.editPageBackgroundAndDesc') }}
    </button>
    {{-- Category Edit Modal --}}

    <x-adminlte-modal id="categoryEdit" title="{{ __('adminlte::adminlte.editPageBackground') }}" theme="warning"
        icon="fas fa-edit" size="xl" v-centered scrollable>
        <form id="formEditCategory"
            action="{{ route('pages.update', ['locale' => app()->getLocale(), 'id' => $page->id]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-12 col-md-6">
                    <x-adminlte-textarea id="content_ar" name="content_ar" label-class="text-olive" igroup-size="md" rows=6
                        label="{{ __('adminlte::adminlte.contentAR') }}" enable-old-support>
                        {{ $page->content }}
                        </x-adminlte.form.textarea>
                </div>

                <div class="col-12 col-md-6"style="direction: ltr;">
                    <x-adminlte-textarea id="content_en" name="content_en" label-class="text-olive" igroup-size="md" rows=6
                        label="{{ __('adminlte::adminlte.contentEN') }}" enable-old-support>
                        {{ $page->content_en }}
                        </x-adminlte.form.textarea>
                </div>
            </div>
            <div class="row">
                <!-- File Upload -->
                @php
                    $initialPreview = [];
                    $initialPreviewConfig = [];
                    $image = $page->background;
                    $previewUrl = asset($image);
                    $initialPreview[] = $previewUrl . '';
                    $initialPreviewConfig[] = [
                        'caption' => basename($image), // The filename for display
                        'size' => Storage::disk('images')->exists($image) ? Storage::disk('images')->size($image) : 0, // File size in bytes
                    ];
                    $config = [
                        'allowedFileTypes' => ['image'],
                        'browseOnZoneClick' => true,
                        'theme' => 'fa5',
                        'overwriteInitial' => true,
                        'initialPreviewAsData' => true,
                        'initialPreview' => $initialPreview, // -- Here is the initial value
                        'initialPreviewConfig' => $initialPreviewConfig,
                        'uploadUrl' => '#',
                        'uploadAsync' => false,
                        'deleteUrl' => localizedRoute('media.destroy', ['id' => 0]), 'initialPreviewShowDelete' => true,
                        'showRemove' => true,
                        'showUpload' => false,
                        'intialRemove'=>false,
                        'showClose' => false,
                        'initialPreviewShowDelete' => false,
                        'fileActionSettings' => [
                            'showRemove' => true,
                            'showZoom' => true,
                            'showUpload' => false,
                            'showDrag' => false,
                            'showRotate' => false,
                        ],
                        'showCancel' => false,
                        ];
                @endphp
                <div class="form-group">
                    {{-- <input type="file" name="files[]"  > --}}
                    <x-adminlte-input-file-krajee name="files" label="{{ __('adminlte::adminlte.background') }}"
                        data-msg-placeholder="Choose a text, office or pdf file..." label-class="text-olive"
                        :config="$config">
                    </x-adminlte-input-file-krajee>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12 d-flex justify-content-center">
                    <x-adminlte-button type="submit" theme="success" icon="fas fa-save"
                        label="{{ __('adminlte::adminlte.save') }}" class="col-12 col-md-6" />
                </div>
            </div>
        </form>

        <x-slot name="footerSlot">
            <x-adminlte-button theme="secondary" label="{{ __('adminlte::adminlte.cancel') }}" data-dismiss="modal" />
        </x-slot>
    </x-adminlte-modal>
    @section('plugins.KrajeeFileinput', true)
@endisset
