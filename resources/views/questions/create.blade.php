@extends('layouts.app')

@section('header', 'إضافة سؤال')

@section('content')
    <!--**********************************
        Content body start
    ***********************************-->
    <div class="content-body">
        <!-- row -->
        <div class="container-fluid">

            <div class="col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="basic-form">
                            <form action="{{ route('question.store') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label for="question" style="font-size: 20px;">نص السؤال</label>
                                    <textarea name="title" class="form-control" rows="4" id="question" required></textarea>
                                </div>

                                <!-- Optional Image Field with Preview -->
                                <div class="mb-3">
                                    <label for="image" style="font-size: 20px;">الصورة (اختياري)</label>
                                    <input type="file" name="image" class="form-control" id="image">
                                    <small class="form-text text-muted">
                                        اذا قمت بإضافة صورة سيظهر هذا السؤال في لعبة الصور
                                    </small>
                                    <!-- Image Preview -->
                                    <img id="imagePreview" src="" alt="Image Preview"
                                        style="display:none; max-width:200px; margin-top:10px;">
                                </div>

                                <div class="mb-3 correct_section">
                                    <label for="question" style="font-size: 20px;">الاجابة الصحيحة</label><br>
                                    <input type="hidden" id="correct_answer">
                                    <textarea class="form-control text-end answer" name="correct_answer" rows="4" id="correct_1" dir="rtl"
                                        style="display: inline; border-color: var(--bs-success); width: 70%;" required></textarea>
                                </div>

                                <nav>
                                    <ul class="pagination pagination-gutter">
                                        <!-- Pagination controls can go here -->
                                    </ul>
                                </nav>

                                <div class="mb-3 wrong_section">
                                    <label for="question" style="font-size: 20px;">الاجابة الخاطئة</label><br>
                                    <input type="hidden" id="wrong_answer">
                                    <textarea class="form-control text-end answer" name="wrong_answer_1" rows="4" id="wrong_1" dir="rtl"
                                        style="display: inline; border-color: var(--bs-danger); width: 70%;" required></textarea>
                                </div>

                                <div class="mb-3 wrong_section">
                                    <label for="question" style="font-size: 20px;">الاجابة الخاطئة</label><br>
                                    <input type="hidden" id="wrong_answer">
                                    <textarea class="form-control text-end answer" name="wrong_answer_2" rows="4" id="wrong_2" dir="rtl"
                                        style="display: inline; border-color: var(--bs-danger); width: 70%;" required></textarea>
                                </div>

                                <div class="mb-3 wrong_section">
                                    <label for="question" style="font-size: 20px;">الاجابة الخاطئة</label><br>
                                    <input type="hidden" id="wrong_answer">
                                    <textarea class="form-control text-end answer" name="wrong_answer_3" rows="4" id="wrong_3" dir="rtl"
                                        style="display: inline; border-color: var(--bs-danger); width: 70%;" required></textarea>
                                </div>

                                <nav>
                                    <ul class="pagination pagination-gutter">
                                        <!-- Pagination controls can go here -->
                                    </ul>
                                </nav>

                                <input type="submit" class="btn btn-secondary" value="إضافة">
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!--**********************************
        Content body end
    ***********************************-->

    <!-- Image Preview Script -->
    <script>
        document.getElementById("image").addEventListener("change", function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = document.getElementById("imagePreview");
                    preview.setAttribute("src", e.target.result);
                    preview.style.display = "block";
                }
                reader.readAsDataURL(file);
            } else {
                document.getElementById("imagePreview").style.display = "none";
            }
        });
    </script>
@endsection
