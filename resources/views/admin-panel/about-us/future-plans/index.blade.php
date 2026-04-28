@extends('adminlte::page')
@section('title', __('adminlte::menu.futurePlans'))

@section('content_header')
    <h1>{{ __('adminlte::menu.futurePlans') }}</h1>
@stop

@section('content')
    @include('admin-panel.modals')


    <div class="container mx-0  mb-5">
        <a href="{{ route('future-plans.create', ['locale'=> app()->getLocale()]) }}">
            <x-adminlte-button class="btn-lg mb-10" type="reset" label="{{ __('adminlte::adminlte.createNewPost') }}" theme="outline-success" icon="fas fa-file-far fa-plus-square	"/>
        </a>
    @include('admin-panel.general.pages.edit')

    </div>
    <!-- Alert for success messages -->

    @include('admin-panel.modals')

    @if (session('error'))
        <div class="container">
            <x-adminlte-card title="{{ __('adminlte::adminlte.error!') }}" theme="danger" theme-mode="outline"
                icon="fas fa-lg fa-exclamation-circle"
                body-class="{{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}"
                header-class="text-uppercase rounded-bottom border-info text-left" removable>
                <i>{{ session('error') }}</i>
            </x-adminlte-card>
        </div>
    @endif

    @php
        $heads = [
            ['label' => 'ID', 'width' => 3, 'classes' => 'border border-white bg-olive'],
            ['label' => __('adminlte::adminlte.title'), 'classes' => 'border border-white bg-olive'],
            ['label' => __('adminlte::adminlte.content'), 'classes' => 'border border-white bg-olive'],
            // ['label' => __('adminlte::adminlte.date'), 'classes' => 'border border-white bg-olive', 'width' => 9],
            ['label' => __('adminlte::adminlte.category'), 'classes' => 'border border-white bg-olive'],
            [
                'label' => __('adminlte::adminlte.procedures'),
                'classes' => 'border border-white bg-olive',
                'no-export' => true,
                'width' => 13,
            ],
        ];
        // dd($posts);
        $data = [];
        $counter = 1;
        foreach ($posts as $post) {
            $postDetail = $post->postDetailOne;

            $btnEdit =
                '<a href="' .
                route('future-plans.edit', ['future_plan' => $post->id, 'locale' => app()->getLocale()]) .
                '" class="btn btn-warning " data-toggle="tooltip" title=' .
                __('adminlte::adminlte.edit') .
                '><i class="	fas fa-edit"></i>'.
                '</a>';
            $btnDelete =
                '<span style="display: inline-block; " data-toggle="tooltip" title="'.__('adminlte::adminlte.delete').' "><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalCustom"  data-id="' .
                $post->id .
                '"'  .
                // __('adminlte::adminlte.delete') .
                '"><i class="fas fa-trash" ></i>'.
                '</button></span>';

            $btnActivation =
                '<span data-toggle="tooltip" title="'.__('adminlte::adminlte.activate').' "><a href="#" class="btn btn-success " data-toggle="modal" data-target="#modalActivate" data-id="' .
                $post->id .
                '">' .
                '<i class="	fas fa-eye"></i>'.
                // __('adminlte::adminlte.activate') .
                '</a></span>';

            $data[] = [
                // $counter++ ?? 'N/A',
                $post->id,
                \Illuminate\Support\Str::limit(strip_tags($postDetail?->title), limit: 75),
                \Illuminate\Support\Str::limit(strip_tags($postDetail?->content), limit: 110),
                $postDetail?->category->name ?? 'N/A',
                $btnEdit .' '. ($post->active ? $btnDelete : $btnActivation  . ' ' . $btnDelete),
            ];
        }
        $config = [
            'data' => $data,
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, null, ['orderable' => false]],
            'escape' => false,
            // 'responsive' => false,
            'scrollX' => true,
            'dom' => 'fBrtp',
            'paging' => true, // Ensure paging is explicitly enabled
            'pagingType' => 'full_numbers',
            // 'serverSide' => true,
            // 'processing' => true,
            // 'ajax' => "{{ localizedRoute('photos.index') }}",
            'language' => [
                'search' => __('adminlte::adminlte.search'),
                'paginate' => [
                    'next' => '&raquo;',
                    'previous' => '&laquo;',
                    'last' => '&raquo;&raquo;',
                    'first' => '&laquo;&laquo;',
                ],
            ],
        ];
    @endphp

    {{-- AdminLTE DataTable --}}
    <x-adminlte-datatable id="table2" :heads="$heads" head-theme="dark" :config="$config" striped hoverable
        with-buttons />

    {{-- Custom --}}
    <x-adminlte-modal tabindex="-1" id="modalCustom" title="{{ __('adminlte::adminlte.caution!') }}" theme="danger"
        icon="fas fa-exclamation-triangle" v-centered>
        <div>{{ __('adminlte::adminlte.deleteCaution') }}</div>
        <div style="font-size: 14px;"><br>{{ __('adminlte::adminlte.deactivateCaution') }}</div>
        <x-slot name="footerSlot">
            <form id="formDelete" action="" method="POST">
                @csrf
                @method('DELETE')
                <x-adminlte-button class="mr-auto" type="submit" theme="outline-danger"
                    label="{{ __('adminlte::adminlte.delete') }}" />
            </form>
            {{-- Form for DEACTIVATE Action --}}
            <form id="formDeactivate" action="" method="POST" style="display:inline;">
                @csrf
                @method('PUT')
                <x-adminlte-button class="mr-auto" type="submit" theme="outline-success"
                    label="{{ __('adminlte::adminlte.deActivate') }}" />
            </form><x-adminlte-button theme="secondary" label="{{ __('adminlte::adminlte.cancel') }}"
                data-dismiss="modal" />
        </x-slot>
    </x-adminlte-modal>
    <x-adminlte-modal tabindex="-1" id="modalActivate" title="{{ __('adminlte::adminlte.activate') }}" theme="success"
        icon="fas fa-question" v-centered>
        <div>{{ __('adminlte::adminlte.activeCaution') }}</div>
        {{-- <iframe src="" frameborder="0" height="200" w></iframe> --}}
        <x-slot name="footerSlot">
            <form id="formActivate" action="" method="POST">
                @csrf
                @method('PUT') {{-- Assuming activation is a PUT request --}}
                <x-adminlte-button class="mr-auto" type="submit" theme="outline-success"
                    label="{{ __('adminlte::adminlte.activate') }}" />
            </form>
            <x-adminlte-button theme="secondary" label="{{ __('adminlte::adminlte.cancel') }}" data-dismiss="modal" />
        </x-slot>
    </x-adminlte-modal>
    {{-- Example button to open modal --}}

@stop


@section('plugins.toastr', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('adminlte_js')

    <script>
        $(document).ready(function() {
            $('#modalCustom').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var postId = button.data('id');

                // Set action for the Delete form
                var deleteAction =
                    '{{ route('future-plans.destroy', ['locale' => app()->getLocale(), 'future_plan' => '__ID__']) }}';
                deleteAction = deleteAction.replace('__ID__', postId);
                $('#formDelete').attr('action', deleteAction);

                // Set action for the Deactivate form
                var deactivateAction = '{{ localizedRoute('post.activation', ['id' => '__ID__']) }}';
                deactivateAction = deactivateAction.replace('__ID__', postId);
                $('#formDeactivate').attr('action', deactivateAction);
            });

            $('#modalActivate').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var postId = button.data('id'); // Extract info from data-* attributes
                // Assuming 'photos.activate' is a route for activating photos. Adjust as needed.
                var action =
                    '{{ localizedRoute('post.activation', ['id' => '__ID__']) }}'; // Use update route for activation
                action = action.replace('__ID__', postId);
                $('#formActivate').attr('action', action);
            });
        });
    </script>

@stop
