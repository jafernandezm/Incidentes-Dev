@vite(['resources/css/resultado/card.css'])

<div class="container_cards-result-escaneo">
    {{-- {{ dd($resultado) }} --}}
    @php
        $data = json_decode($resultado['data'], true);
    @endphp
    {{-- {{dd($data)}} --}}
    @if (is_array($data))
        @foreach ($data as $item)
            <div class="card_result-esc">
                <div class="content__card_result-esc-text">
                {{-- {{dd($item)}} --}}
                        <div class="card-header">
                            <p><strong>URL:</strong> {{ $item['URL_ORIGEN'] ?? 'N/A' }}</p>
                            <p><strong>Tipo:</strong> {{ $item['tipo'] ?? 'N/A' }}</p>
                        </div>
                        <hr>
                        <div class="details hidden">
                            <ul class="list-disc">
                                
                                @foreach ($item as $key => $value)
                                <li>
                                    <strong>{{ ucfirst($key) }}:</strong>
                                    @if ($key === 'html_infectado' || $key === 'html')
                                        @if (is_array($value))
                                            <ul>
                                                @foreach ($value as $subValue)
                                                    <li>
                                                        <div class="code-html">
                                                            <pre><code class="language-html">
                                                                {!! htmlspecialchars(is_string($subValue) ? $subValue : json_encode($subValue, JSON_PRETTY_PRINT), ENT_QUOTES, 'UTF-8') !!}
                                                            </code></pre>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="code-html">
                                                <pre><code class="language-html">
                                                    {!! htmlspecialchars(is_string($value) ? $value : json_encode($value, JSON_PRETTY_PRINT), ENT_QUOTES, 'UTF-8') !!}
                                                </code></pre>
                                            </div>
                                        @endif
                                    @elseif (is_string($value) && (strpos($value, 'selector') !== false || strpos($value, 'return') !== false || strpos($value, 'function') !== false))
                                        <div class="code-javascript">
                                            <pre><code class="language-javascript">
                                                {{ is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value }}
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
            <div class="back_number"></div>
            <div class="front_number">
                <h1> @if ( $loop->iteration < 10 ) 0{{ $loop->iteration }} @else {{ $loop->iteration }} @endif </h1>
            </div>
            <div class="right_tip"></div>
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
