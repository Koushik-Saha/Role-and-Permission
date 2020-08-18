        {{-- Vendor Scripts --}}
        <script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/ui/prism.min.js')) }}"></script>
        @yield('vendor-script')
        {{-- Theme Scripts --}}
        <script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>
        <script src="{{ asset(mix('js/core/app.js')) }}"></script>
        <script src="{{ asset(mix('js/scripts/components.js')) }}"></script>


        <!-- Customized JS files -->

        <!-- Select vendor files -->
        <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>

        {{-- Datatable vendor files --}}
        <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>

        <!-- DatePicker vendor files -->
        <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>

        <!-- Number Input vendor files -->
        <script src="{{ asset(mix('vendors/js/forms/spinner/jquery.bootstrap-touchspin.js')) }}"></script>

        <!-- Validation vendor files -->
        <script src="{{ asset(mix('vendors/js/forms/validation/jqBootstrapValidation.js')) }}"></script>

        {{--    switchery Toggle--}}
        <script src="{{ asset('js/scripts/switchery/switchery.min.js') }}"></script>

        <!-- Toastr vendor files -->
        <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
        <script>
            @if(Session::has('message'))
          var type = "{{ Session::get('alert-type', 'info') }}";
          switch(type){
            case 'info':
              toastr.info("{{ Session::get('message') }}");
              break;

            case 'warning':
              toastr.warning("{{ Session::get('message') }}");
              break;

            case 'success':
              toastr.success("{{ Session::get('message') }}");
              break;

            case 'error':
              toastr.error("{{ Session::get('message') }}");
              break;
          }
          @endif
        </script>

        <!-- UniSharpe File Manager vendor files -->

{{--        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>--}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
        <script>
            var route_prefix = "/filemanager";
        </script>

        <script>
            {!! \File::get(base_path('vendor/unisharp/laravel-filemanager/public/js/stand-alone-button.js')) !!}
        </script>
        <script>
            $('#lfm').filemanager('image', {prefix: route_prefix});
            // $('#lfm').filemanager('file', {prefix: route_prefix});
        </script>

        <script>
            var lfm = function (id, type, options) {
                let button = document.getElementById(id);

                button.addEventListener('click', function () {
                    var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';
                    var target_input = document.getElementById(button.getAttribute('data-input'));
                    var target_preview = document.getElementById(button.getAttribute('data-preview'));

                    window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
                    window.SetUrl = function (items) {
                        var file_path = items.map(function (item) {
                            return item.url;
                        }).join(',');

                        // set the value of the desired input to image url
                        target_input.value = file_path;
                        target_input.dispatchEvent(new Event('change'));

                        // clear previous preview
                        target_preview.innerHtml = '';

                        // set or change the preview image src
                        items.forEach(function (item) {
                            let img = document.createElement('img')
                            img.setAttribute('style', 'height: 5rem')
                            img.setAttribute('src', item.thumb_url)
                            target_preview.appendChild(img);
                        });

                        // trigger change event
                        target_preview.dispatchEvent(new Event('change'));
                    };
                });
            };

            lfm('lfm2', 'file', {prefix: route_prefix});
        </script>

        <!-- Tinymce Editor vendor files -->
        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
        <script>
            var editor_config = {
                path_absolute: "",
                selector: "textarea[name=project_description]",
                plugins: [
                    "link image"
                ],
                relative_urls: false,
                height: 129,
                width: 1000,
                file_browser_callback: function (field_name, url, type, win) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

                    var cmsURL = editor_config.path_absolute + route_prefix + '?field_name=' + field_name;
                    if (type == 'image') {
                        cmsURL = cmsURL + "&type=Images";
                    } else {
                        cmsURL = cmsURL + "&type=Files";
                    }

                    tinyMCE.activeEditor.windowManager.open({
                        file: cmsURL,
                        title: 'Filemanager',
                        width: x * 0.8,
                        height: y * 0.8,
                        resizable: "yes",
                        close_previous: "no"
                    });
                }
            };

            tinymce.init(editor_config);
        </script>



@if($configData['blankPage'] == false)
        <script src="{{ asset(mix('js/scripts/customizer.js')) }}"></script>
        <script src="{{ asset(mix('js/scripts/footer.js')) }}"></script>
@endif

        <!-- Customized JS files -->

        <!-- Select Page js files -->
        <script src="{{ asset(mix('js/scripts/forms/select/form-select2.js')) }}"></script>

        <!-- Quill Editor Page js files -->
{{--        <script src="{{ asset(mix('js/scripts/editors/editor-quill.js')) }}"></script>--}}

        <!-- DatePicker Page js files -->
        <script src="{{ asset(mix('js/scripts/pickers/dateTime/pick-a-datetime.js')) }}"></script>

        <!-- Number Input Page js files -->
        <script src="{{ asset(mix('js/scripts/forms/number-input.js')) }}"></script>

        <!-- File Uploader Page js files -->
{{--        <script src="{{ asset(mix('js/scripts/extensions/dropzone.js')) }}"></script>--}}

        <!-- Validation Page js files -->
        <script src="{{ asset(mix('js/scripts/forms/validation/form-validation.js')) }}"></script>

        <!-- Toastr Page js files -->
        <script src="{{ asset(mix('js/scripts/extensions/toastr.js')) }}"></script>

        {{-- Datatable Page js files --}}
        <script src="{{ asset(mix('js/scripts/datatables/datatable.js')) }}"></script>

        {{-- page script --}}
        @yield('page-script')
        {{-- page script --}}
