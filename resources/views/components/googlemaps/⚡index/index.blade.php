<div class="h-[80vh] w-full">
    <!-- =========================================================
        CONTAINER DO MAPA
        - Altura fixa em 86vh
        - Flex para ocupar toda a área disponível
    ========================================================== -->
    <div class="h-[86vh] flex flex-col">

        {{-- MAPA --}}
        <div
            id="map"
            wire:ignore
            class="flex-1 w-full rounded-lg"
            style="background:#eee"
        ></div>

    </div>

    <!-- =========================================================
        JAVASCRIPT
    ========================================================== -->
    <script>
        
        /* =====================================================
            VARIÁVEIS GLOBAIS
        ====================================================== */
        let map;              // Instância do Google Maps
        let markers = [];     // Lista de pins no mapa
        let infoWindow;       // Popover reutilizável
        let markerCluster;    // Cluster de pins próximos

        /* =====================================================
            FUNÇÃO: PIN COM PREÇO + SETA (SVG)
            - Cor muda conforme status
            - Texto mostra o valor do imóvel
            - A seta aponta exatamente para a coordenada
        ====================================================== */
        function getPriceMarker(price, status) {
            const colors = {
                active: '#2bb594',   // verde
                inactive: '#efad25', // amarelo
                sold: '#d33d49',     // vermelho
            };

            // Texto exibido no pin
            const label = price
                ? Number(price).toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL',
                })
                : 'Sob consulta';

            const color = colors[status] ?? '#6b7280';

            // SVG do pin
            const svg = `
                <svg xmlns="http://www.w3.org/2000/svg" width="110" height="40" viewBox="0 0 110 40">
                    <!-- Fundo -->
                    <rect x="0" y="0"
                          rx="18" ry="18"
                          width="110" height="36"
                          fill="${color}" />

                    <!-- Texto -->
                    <text x="55" y="23"
                          font-size="13"
                          fill="white"
                          text-anchor="middle"
                          font-family="Arial"
                          font-weight="bold">
                        ${label}
                    </text>

                    <!-- Seta -->
                    <path d="M57 36 L63 36 L60 46 Z" fill="${color}" />
                </svg>
            `;

            return {
                url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg),
                scaledSize: new google.maps.Size(110, 46),
                anchor: new google.maps.Point(60, 46), // ponta da seta
            };
        }

        /* =====================================================
            ESTILO PERSONALIZADO DO MAPA
            - Remove borda do texto
            - Cores suaves e clean
        ====================================================== */
        const MAP_STYLE = [
            {
                featureType: "all",
                elementType: "labels.text.fill",
                stylers: [{ color: "#6b7280" }]
            },
            {
                featureType: "all",
                elementType: "labels.text.stroke",
                stylers: [{ visibility: "off" }]
            },
            {
                featureType: "road",
                elementType: "geometry",
                stylers: [{ color: "#e5e7eb" }]
            },
            {
                featureType: "water",
                elementType: "geometry",
                stylers: [{ color: "#c7d2fe" }]
            },
            {
                featureType: "landscape",
                elementType: "geometry",
                stylers: [{ color: "#f8fafc" }]
            }
        ];

        /* =====================================================
            INICIALIZA O MAPA
        ====================================================== */
        window.initMap = function () {

            // Produtos vindos do backend
            const products = @json($products);

            // Criação do mapa
            map = new google.maps.Map(document.getElementById('map'), {
                center: products.length
                    ? { lat: products[0].lat, lng: products[0].lng }
                    : { lat: -11.853975030976676, lng: -55.512032928804146 },
                zoom: products.length ? 13 : 4,
                //styles: MAP_STYLE,

                //scrollwheel: false, // 🔒 bloqueia zoom com scroll
            });

            // InfoWindow único (reutilizado)
            infoWindow = new google.maps.InfoWindow();

            // Renderiza os pins
            updateMarkers(products);
        };

        /* =====================================================
            ATUALIZA OS PINS DO MAPA
            - Remove pins antigos
            - Cria novos
            - Aplica cluster
            - Centraliza automaticamente
        ====================================================== */
        function updateMarkers(products) {

            // Remove pins antigos
            markers.forEach(m => m.setMap(null));
            markers = [];

            // Limpa cluster anterior
            if (markerCluster) {
                markerCluster.clearMarkers();
            }

            // Bounds para centralização automática
            const bounds = new google.maps.LatLngBounds();

            products.forEach(p => {

                // Ignora se não tiver coordenadas
                if (!p.lat || !p.lng) return;

                // Cria o pin
                const marker = new google.maps.Marker({
                    position: { lat: p.lat, lng: p.lng },
                    title: p.cod ?? 'Imóvel',
                    icon: getPriceMarker(p.amount, p.status),
                });

                /* ================================
                    POPOVER (InfoWindow)
                ================================= */
                const content = `
                    <span style="max-width:300px;font-family:Arial,sans-serif;">
                        ${p.image
                            ? `<img src="${p.image}"
                                    style="width:300px;height:200px;
                                           object-fit:cover;
                                           border-radius:8px;
                                           margin-bottom:10px">`
                            : `<span style="width:300px;height:200px;
                                           background:#e5e7eb;
                                           border-radius:8px;
                                           display:flex;
                                           align-items:center;
                                           justify-content:center;
                                           color:#9ca3af;
                                           margin-bottom:10px">
                                    Sem imagem
                               </span>`
                        }

                        <span>
                            <span style="font-weight:600;font-size:16px">
                                Cod.: ${p.cod ?? '—'}
                            </span>
                            <br/>

                            <span style="font-size:12px;color:#6b7280">
                                Ref.: ${p.ref ?? '—'}
                            </span>
                            <br/><br/>

                            <span style="margin-top:10px;
                                         font-weight:700;
                                         color:#0f766e;
                                         font-size:16px">
                                ${p.amount
                                    ? Number(p.amount).toLocaleString('pt-BR', {
                                        style: 'currency',
                                        currency: 'BRL',
                                    })
                                    : 'Valor sob consulta'
                                }
                            </span>
                        </span>
                    </span>
                `;

                // Evento de clique no pin
                marker.addListener('click', () => {
                    infoWindow.close();
                    infoWindow.setContent(content);
                    infoWindow.open(map, marker);
                });

                markers.push(marker);

                // Expande bounds para centralização
                bounds.extend({ lat: p.lat, lng: p.lng });
            });

            /* ================================
                CLUSTER DE PINS
            ================================= */
            markerCluster = new markerClusterer.MarkerClusterer({
                map,
                markers,
            });

            /* ================================
                CENTRALIZAÇÃO AUTOMÁTICA
            ================================= */
            if (products.length > 1) {
                map.fitBounds(bounds);
            } else if (products.length === 1) {
                map.setCenter(bounds.getCenter());
                map.setZoom(15);
            }
        }

        /* =====================================================
            LIVEWIRE: ATUALIZA MAPA QUANDO FILTROS MUDAM
        ====================================================== */
        document.addEventListener('livewire:init', () => {
            Livewire.on('update-map', e => updateMarkers(e.products));
        });
    </script>

    <!-- =========================================================
        GOOGLE MAPS
    ========================================================== -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_KEY') }}&callback=initMap"
        async
        defer
    ></script>

    <!-- =========================================================
        MARKER CLUSTER
    ========================================================== -->
    <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>

    <script>
document.addEventListener('wheel', function (e) {
    if (e.ctrlKey) {
        e.preventDefault();
    }
}, { passive: false });
</script>

</div>
