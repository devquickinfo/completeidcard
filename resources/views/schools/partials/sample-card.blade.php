<div class="col-md-4 mb-4">

    <div class="card h-100">

        <div class="card-header bg-primary text-white d-flex align-items-center">

            <h3 class="card-title mb-0 flex-grow-1">

                @if(session('role') === 'school' || session('viewing_school'))

                    <label class="mb-0 d-flex align-items-center">

<input
    type="radio"
    name="{{ $all->orientation }}_sample"
    value="{{ $all->id }}"
    class="sample-radio mr-2"
    data-orientation="{{ $all->orientation }}"
    @if(
        $selectedSamples->get($all->orientation) == $all->id
    )
        checked
    @endif
>

                       {{ Str::limit($all->name, 20, '...') }}

                    </label>

                @else

                    {{ Str::limit($all->name, 20, '...') }}

                @endif

            </h3>


            {{-- DELETE --}}

            @if(session('role') !== 'school' || $all->school_id == Auth::user()->school_id)

                <form
                    action="{{ route('upload-samples.destroy', $all->id) }}"
                    method="POST"
                    class="ml-auto">

                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-sm btn-danger">

                        <i class="fas fa-trash"></i>

                    </button>

                </form>

            @endif

        </div>


        <div class="card-body">

            @if($all->caption)

                <p class="mb-2">
                    {{ $all->caption }}
                </p>

            @endif


            <div class="sample-image-wrapper">

                <img
                    src="{{ asset('storage/' . $all->file_path) }}"
                    alt="{{ $all->name }}"
                    class="sample-image">

            </div>

        </div>

    </div>

</div>