@isset($categories)
<div class="container-fluid p-0 mb-4">
    <div class="d-flex flex-nowrap overflow-auto pb-2 px-3 category-scroll-container" style="scrollbar-width: none; -ms-overflow-style: none;">
        @foreach ($categories as $cat)
            <div class="me-3 mb-6 border-4 bg-gradient" style="min-width: 120px; flex: 0 0 auto;">
                <a href="{{ route('category.products', $cat->id) }}" class="text-decoration-none text-warning">
                    <div class="p-3 b-4 rounded text-center border border-light-subtle hover-effect">
                        <div class="small fw-semibold text-truncate">
                            {{ Str::limit($cat->name, 15) }}
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

<style>
    .hover-effect {
        transition: all 0.3s ease;
    }
    .hover-effect:hover {
        background-color: var(--bs-light-bg-subtle) !important;
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-color: var(--bs-primary) !important;
    }
    
    /* Hide scrollbar for Chrome, Safari and Opera */
    .category-scroll-container::-webkit-scrollbar {
        display: none;
    }
    
    /* Hide scrollbar for IE, Edge and Firefox */
    .category-scroll-container {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Enable horizontal scrolling with mouse wheel
        $('.category-scroll-container').on('wheel', function(e) {
            if (e.originalEvent.deltaY > 0) {
                this.scrollLeft += 50;
            } else {
                this.scrollLeft -= 50;
            }
            e.preventDefault();
        });
    });
</script>
@endisset