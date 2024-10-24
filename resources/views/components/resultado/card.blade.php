@vite(['resources/css/resultado/card.css'])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6 p-6">
    {{-- {{ dd($resultado) }} --}}
    @php
        $data = json_decode($resultado['data'], true);
    @endphp
    {{-- {{dd($data)}} --}}
    @if (is_array($data))
        @foreach ($data as $item)
            <div class="card">
                {{-- {{dd($item)}} --}}
                <div class="card-header">
                    <p><strong>URL:</strong> {{ $item['URL_ORIGEN'] ?? 'N/A' }}</p>
                    <p><strong>Tipo:</strong> {{ $item['tipo'] ?? 'N/A' }}</p>
                </div>
                <div class="details hidden">
                    <ul class="list-disc">
                    
                        @foreach ($item as $key => $value)
                        {{-- {{dd($key)}} --}}
                        <li>
                            <strong>{{ ucfirst($key) }}:</strong>
                            @if ($key === 'html_infectado' || $key === 'html')
                                <div class="code-html">
                                    <pre><code class="language-html">
                                        {!! htmlspecialchars(is_array($value) ? implode("\n", $value) : $value, ENT_QUOTES, 'UTF-8') !!}
                                    </code></pre>
                                </div>
                            @elseif (is_string($value) && (strpos($value, 'selector') !== false || strpos($value, 'return') !== false || strpos($value, 'function') !== false))
                                <div class="code-javascript">
                                    <pre><code class="language-javascript">
                                        {{ is_array($value) ? implode("\n", $value) : $value }}
                                    </code></pre>
                                </div>
                            @else
                                <div class="default-text">
                                    {{ is_array($value) ? implode("\n", $value) : $value }}
                                </div>
                            @endif
                        </li>
                    @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.card');

    cards.forEach(card => {
        card.addEventListener('click', () => {
            const details = card.querySelector('.details');
            if (details) {
                details.classList.toggle('hidden');
            }
        });
    });
});
</script>
