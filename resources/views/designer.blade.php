<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>T-Shirt Designer Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Arial&family=Anton&family=Fredoka+One&family=Oswald:wght@400;700&family=Pacifico&family=Poppins&family=Quicksand:wght@400;700&family=Righteous&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.5.0/fabric.min.js"></script>
    <style>
        .design-area-container {
            position: relative;
            overflow:hidden;
            width: 100%;
            height: 600px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        #design-area {
            position: absolute;
            top: 0;
            left: 0;
        }
        .tool-btn {
            transition: all 0.2s;
        }
        .tool-btn:hover {
            background-color: #edf2f7;
        }
        .active-view {
            background-color: #4299e1;
            color: white;
        }
        .active-tool {
            background-color: #4299e1;
            color: white;
        }
        .object-toolbar {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 100;
            background: white;
            padding: 5px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .zoom-controls {
            position: absolute;
            bottom: 10px;
            right: 10px;
            z-index: 100;
            background: white;
            padding: 5px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .cliparts-container {
            height: 300px;
            overflow-y: auto;
        }
        .draggable-clipart {
            cursor: pointer;
            transition: transform 0.2s;
        }
        .draggable-clipart:hover {
            transform: scale(1.05);
        }
        .color-option {
            transition: transform 0.2s;
        }
        .color-option:hover {
            transform: scale(1.1);
        }
        .layer-item:hover {
            background-color: #f0f4f8;
        }
        #design-preview {
            transition: all 0.3s ease;
        }
        #design-preview:hover {
            transform: scale(1.05);
        }
        .category-btn.active {
            background-color: #4299e1;
            color: white;
        }
        .font-option-btn {
            transition: all 0.2s;
            font-family: var(--font);
        }
        .font-option-btn:hover {
            background-color: #f0f4f8;
        }
        .font-option-btn.bg-blue-100 {
            background-color: #dbeafe;
            border-color: #93c5fd;
        }
        .text-style-btn, .text-align-btn {
            transition: all 0.2s;
        }
        .text-style-btn:hover, .text-align-btn:hover {
            background-color: #f0f4f8;
        }
        .text-style-btn.active, .text-align-btn.active {
            background-color: #dbeafe;
            border-color: #93c5fd;
        }
        /* Set font families for the buttons */
        button[data-font="Arial"] { font-family: Arial, sans-serif; }
        button[data-font="Oswald"] { font-family: 'Oswald', sans-serif; }
        button[data-font="Anton"] { font-family: 'Anton', sans-serif; }
        button[data-font="Pacifico"] { font-family: 'Pacifico', cursive; }
        button[data-font="Quicksand"] { font-family: 'Quicksand', sans-serif; }
        button[data-font="Righteous"] { font-family: 'Righteous', cursive; }
        button[data-font="Fredoka One"] { font-family: 'Fredoka One', sans-serif; }
        button[data-font="Poppins"] { font-family: 'Poppins', sans-serif; }
        /* Custom font class for MONOTOR */
        .font-monotor {
            font-family: 'MONOTOR', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">  
    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow">
        <div class="container mx-auto px-4 py-2 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span class="font-bold text-xl">T-Shirt Designer Pro</span>
                <div class="flex space-x-2">
                    <button class="tool-btn px-3 py-1 rounded" id="file-menu-btn"><i class="fas fa-file mr-1"></i> Designs</button>
                    <button class="tool-btn px-3 py-1 rounded"><i class="fas fa-question-circle mr-1"></i> Help</button>
                    <button id="undo-btn" class="tool-btn px-3 py-1 rounded"><i class="fas fa-undo mr-1"></i> Undo</button>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    <span class="font-bold">$99.99</span>
                </div>
                <button id="proceed-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    ADD TO CART <i class="fas fa-arrow-right ml-1"></i>
                </button>
                <button class="tool-btn px-3 py-1 rounded">
                    BACK TO SHOP
                </button>
            </div>
        </div>
    </nav>

    <!-- Designs Modal -->
    <div id="designs-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 w-3/4 max-w-4xl max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Saved Designs</h3>
                <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="saved-designs-container">
                <!-- Designs will be loaded here -->
            </div>
        </div>
    </div>

    <div class="container mx-auto p-4">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Left Sidebar - Cliparts and Templates -->
            <div class="w-full lg:w-1/5 bg-white rounded shadow">
                <div class="p-4 border-b">
                    <div class="relative">
                        <input type="text" placeholder="Search cliparts..." class="w-full p-2 border rounded pl-8" id="search-cliparts">
                        <i class="fas fa-search absolute left-2 top-3 text-gray-400"></i>
                    </div>
                </div>
                
                <div class="p-2 border-b">
                    <div class="flex overflow-x-auto">
                        <button class="category-btn px-3 py-1 rounded mr-1 whitespace-nowrap active" data-category="all">All categories</button>
                        <button class="category-btn px-3 py-1 rounded mr-1 whitespace-nowrap" data-category="templates">TEMPLATES</button>
                        <button class="category-btn px-3 py-1 rounded mr-1 whitespace-nowrap" data-category="cliparts">CLIPARTS</button>
                        <button class="category-btn px-3 py-1 rounded mr-1 whitespace-nowrap" data-category="text">TEXT</button>
                        <button class="category-btn px-3 py-1 rounded mr-1 whitespace-nowrap" data-category="images">IMAGES</button>
                        <button class="category-btn px-3 py-1 rounded mr-1 whitespace-nowrap" data-category="shapes">SHAPES</button>
                    </div>
                </div>
                
                <div class="cliparts-container p-4" id="cliparts-container">
                    <h3 class="font-semibold mb-3">Popular Designs</h3>
                    <div class="grid grid-cols-3 gap-3" id="cliparts-grid">
                        <!-- Cliparts will be loaded here dynamically -->
                    </div>
                </div>
            </div>
            
            <!-- Main Design Area -->
            <div class="w-full lg:w-3/5">
                <div class="bg-white rounded shadow p-4">
                    <!-- View Controls -->
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex space-x-1">
                            <button class="view-btn px-3 py-1 rounded active-view" data-view="front">FRONT</button>
                            <button class="view-btn px-3 py-1 rounded" data-view="back">BACK</button>
                            <button class="view-btn px-3 py-1 rounded" data-view="left">LEFT</button>
                            <button class="view-btn px-3 py-1 rounded" data-view="right">RIGHT</button>
                        </div>
                        <div class="text-sm text-gray-600">
                            EVOLUTION LEGEND: <span class="font-semibold">Stages design current colors used</span>
                        </div>
                    </div>
                    
                    <!-- Design Area Container -->
                    <div class="design-area-container">
                        <canvas id="design-area" width="700" height="500"></canvas>
                        
                        <!-- Object Toolbar -->
                        <div class="object-toolbar flex space-x-1" id="object-toolbar" style="display: none;">
                            <button class="tool-btn p-2 rounded border" title="Bring Forward" id="bring-forward">
                                <i class="fas fa-arrow-up"></i>
                            </button>
                            <button class="tool-btn p-2 rounded border" title="Send Backward" id="send-backward">
                                <i class="fas fa-arrow-down"></i>
                            </button>
                            <button class="tool-btn p-2 rounded border" title="Delete" id="delete-object">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button class="tool-btn p-2 rounded border" title="Duplicate" id="duplicate-object">
                                <i class="fas fa-copy"></i>
                            </button>
                            <button class="tool-btn p-2 rounded border" title="Flip Horizontal" id="flip-horizontal">
                                <i class="fas fa-arrows-alt-h"></i>
                            </button>
                            <button class="tool-btn p-2 rounded border" title="Flip Vertical" id="flip-vertical">
                                <i class="fas fa-arrows-alt-v"></i>
                            </button>
                        </div>
                        
                        <!-- Zoom Controls -->
                        <div class="zoom-controls flex items-center space-x-2">
                            <button class="tool-btn p-1 rounded border" id="zoom-out">
                                <i class="fas fa-search-minus"></i>
                            </button>
                            <span id="zoom-level">100%</span>
                            <button class="tool-btn p-1 rounded border" id="zoom-in">
                                <i class="fas fa-search-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Text Controls -->
                <div class="mt-4 bg-white rounded shadow p-4">
                    <div class="flex items-center space-x-4 mb-4">
                        <input type="text" id="text-input" placeholder="Enter your text here..." class="flex-1 p-2 border rounded">
                        <button id="add-text" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Add Text
                        </button>
                    </div>
                    
                    <!-- Font Selection Grid -->
                    <div class="mb-4">
                        <h4 class="font-semibold mb-2">Text Font</h4>
                        <div class="grid grid-cols-3 gap-2">
                            <button class="font-option-btn p-2 border rounded text-sm" data-font="Arial">Arial</button>
                            <button class="font-option-btn p-2 border rounded text-sm" data-font="Oswald">Oswald</button>
                            <button class="font-option-btn p-2 border rounded text-sm" data-font="Anton">Anton</button>
                            <button class="font-option-btn p-2 border rounded text-sm font-monotor" data-font="MONOTOR">MONOTOR</button>
                            <button class="font-option-btn p-2 border rounded text-sm" data-font="Pacifico">Pacifico</button>
                            <button class="font-option-btn p-2 border rounded text-sm" data-font="Quicksand">Quicksand</button>
                            <button class="font-option-btn p-2 border rounded text-sm" data-font="Righteous">Righteous</button>
                            <button class="font-option-btn p-2 border rounded text-sm" data-font="Fredoka One">Fredoka One</button>
                            <button class="font-option-btn p-2 border rounded text-sm" data-font="Poppins">Poppins</button>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-4 mt-3">
                        <!-- Text Style Buttons -->
                        <div class="flex space-x-1">
                            <button class="text-style-btn p-2 rounded border" id="text-bold" title="Bold">
                                <i class="fas fa-bold"></i> B
                            </button>
                            <button class="text-style-btn p-2 rounded border" id="text-italic" title="Italic">
                                <i class="fas fa-italic"></i> I
                            </button>
                            <button class="text-style-btn p-2 rounded border" id="text-underline" title="Underline">
                                <i class="fas fa-underline"></i> U
                            </button>
                        </div>
                        
                        <!-- Text Alignment -->
                        <div class="flex space-x-1">
                            <button class="text-align-btn p-2 rounded border active" id="text-align-left" title="Align Left">
                                <i class="fas fa-align-left"></i>
                            </button>
                            <button class="text-align-btn p-2 rounded border" id="text-align-center" title="Align Center">
                                <i class="fas fa-align-center"></i>
                            </button>
                            <button class="text-align-btn p-2 rounded border" id="text-align-right" title="Align Right">
                                <i class="fas fa-align-right"></i>
                            </button>
                        </div>
                        
                        <!-- Text Color -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm">Color:</span>
                            <input type="color" id="text-color" value="#000000" class="h-8 w-8">
                        </div>
                        
                        <!-- Stroke Options -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm">Stroke:</span>
                            <input type="range" id="stroke-width" min="0" max="10" value="0" class="w-20">
                            <span id="stroke-width-value">0px</span>
                            <input type="color" id="stroke-color" value="#000000" class="h-6 w-6">
                        </div>
                        
                        <!-- Transparency -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm">Opacity:</span>
                            <input type="range" id="text-opacity" min="0" max="100" value="100" class="w-20">
                            <span id="opacity-value">100%</span>
                        </div>
                        
                        <!-- Font Size -->
                        <div class="flex items-center space-x-2">
                            <span class="text-sm">Size:</span>
                            <select id="font-size" class="border rounded p-1">
                                <option value="12">12px</option>
                                <option value="18">18px</option>
                                <option value="24">24px</option>
                                <option value="36" selected>36px</option>
                                <option value="48">48px</option>
                                <option value="64">64px</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Sidebar - Layers and Colors -->
            <div class="w-full lg:w-1/5 bg-white rounded shadow">
                <div class="p-4 border-b">
                    <h3 class="font-semibold">Layers</h3>
                </div>
                <div class="p-4" id="layers-list">
                    <p class="text-gray-500 text-sm">No objects in design yet</p>
                </div>
                
                <div class="p-4 border-t">
                    <h3 class="font-semibold mb-2">T-Shirt Color</h3>
                    <div class="grid grid-cols-5 gap-2">
                        <div class="color-option h-8 w-8 bg-white border-2 border-blue-500 rounded cursor-pointer" data-color="white"></div>
                        <div class="color-option h-8 w-8 bg-black rounded cursor-pointer" data-color="black"></div>
                        <div class="color-option h-8 w-8 bg-red-500 rounded cursor-pointer" data-color="#ef4444"></div>
                        <div class="color-option h-8 w-8 bg-blue-500 rounded cursor-pointer" data-color="#3b82f6"></div>
                        <div class="color-option h-8 w-8 bg-green-500 rounded cursor-pointer" data-color="#10b981"></div>
                        <div class="color-option h-8 w-8 bg-yellow-500 rounded cursor-pointer" data-color="#f59e0b"></div>
                        <div class="color-option h-8 w-8 bg-purple-500 rounded cursor-pointer" data-color="#8b5cf6"></div>
                        <div class="color-option h-8 w-8 bg-pink-500 rounded cursor-pointer" data-color="#ec4899"></div>
                        <div class="color-option h-8 w-8 bg-gray-500 rounded cursor-pointer" data-color="#6b7280"></div>
                        <div class="color-option h-8 w-8 bg-indigo-500 rounded cursor-pointer" data-color="#6366f1"></div>
                    </div>
                </div>
                
                <div class="p-4 border-t">
                    <h3 class="font-semibold mb-2">Current Design</h3>
                    <div class="border rounded p-2">
                        <img id="design-preview" src="/images/tshirt-front.png" alt="Design Preview" class="w-full">
                    </div>
                    <div class="mt-3">
                        <input type="text" id="design-title" placeholder="Design Name" class="w-full p-2 border rounded">
                        <button id="save-design" class="w-full mt-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                            <i class="fas fa-save mr-1"></i> Save Design
                        </button>
                        <button id="export-design" class="w-full mt-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            <i class="fas fa-download mr-1"></i> Export Image
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MONOTOR font (if not available through Google Fonts) -->
    <style>
        @font-face {
            font-family: 'MONOTOR';
            src: url('/fonts/MONOTOR-Regular.woff2') format('woff2'),
                 url('/fonts/MONOTOR-Regular.woff') format('woff');
            font-weight: normal;
            font-style: normal;
            font-display: swap;
        }
        .font-monotor {
            font-family: 'MONOTOR', sans-serif;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Initialize Fabric.js canvas
            var canvas = new fabric.Canvas('design-area', {
                selection: true,
                preserveObjectStacking: true,
                backgroundColor: '#f8fafc'
            });
            
            // Current zoom level
            var zoomLevel = 1;
            
            // Current view (front/back/left/right)
            var currentView = 'front';

            // Current color
            var currentColor = 'white';
            
            // Undo stack
            var undoStack = [];
            var undoPointer = -1;
            
            // T-shirt base image reference
            var tshirtBase = null;
            
            // Load initial t-shirt view
            loadTshirtView(currentView, currentColor);
            
            // Load cliparts
            loadCliparts();
            
            // View buttons (front/back/left/right) 
            $('.view-btn').click(function() {
                $('.view-btn').removeClass('active-view');
                $(this).addClass('active-view');
                currentView = $(this).data('view');
                saveState();
                loadTshirtView(currentView, currentColor);
            });
            
            // Function to load t-shirt view
            function loadTshirtView(view, color = 'white') {
                // In a real app, you would have different images for each view 
                 var imageUrl = `/images/tshirt-${view}-${color}.png`;
                
                canvas.clear();
                fabric.Image.fromURL(imageUrl, function(img) {
                    img.set({
                        left: canvas.width / 2,
                        top: canvas.height / 2,
                        originX: 'center',
                        originY: 'center',
                        selectable: false,
                        hasControls: false,
                        lockMovementX: true,
                        lockMovementY: true,
                        lockRotation: true,
                        lockScalingX: true,
                        lockScalingY: true,
                        name: 'tshirt-base'
                    });
                    
                    // Scale to fit canvas
                    var scale = Math.min(
                        (canvas.width * 0.8) / img.width,
                        (canvas.height * 0.8) / img.height
                    );
                    img.scale(scale);
                    
                    canvas.add(img);
                    tshirtBase = img;
                    canvas.renderAll();
                    $('#design-preview').attr('src', imageUrl);
                    
                    // Restore other objects if switching views
                    if (undoPointer >= 0 && undoStack[undoPointer][view]) {
                        canvas.loadFromJSON(undoStack[undoPointer][view], function() {
                            // After loading, we need to reset the tshirt base properties
                            var objects = canvas.getObjects();
                            objects.forEach(function(obj) {
                                if (obj.name === 'tshirt-base') {
                                    obj.set({
                                        selectable: false,
                                        hasControls: false,
                                        lockMovementX: true,
                                        lockMovementY: true,
                                        lockRotation: true,
                                        lockScalingX: true,
                                        lockScalingY: true
                                    });
                                    tshirtBase = obj;
                                }
                            });
                            canvas.renderAll();
                            updateLayersList();
                        });
                    }
                });
            }
            
            // Zoom controls
            $('#zoom-in').click(function() {
                zoomLevel += 0.1;
                updateZoom();
            });
            
            $('#zoom-out').click(function() {
                zoomLevel = Math.max(0.1, zoomLevel - 0.1);
                updateZoom();
            });
            
            function updateZoom() {
                canvas.setZoom(zoomLevel);
                $('#zoom-level').text(Math.round(zoomLevel * 100) + '%');
            }
            
            // Object controls
            $('#bring-forward').click(function() {
                var activeObject = canvas.getActiveObject();
                if (activeObject) {
                    activeObject.bringForward();
                    canvas.renderAll();
                    updateLayersList();
                    saveState();
                }
            });
            
            $('#send-backward').click(function() {
                var activeObject = canvas.getActiveObject();
                if (activeObject) {
                    activeObject.sendBackwards();
                    canvas.renderAll();
                    updateLayersList();
                    saveState();
                }
            });
            
            $('#delete-object').click(function() {
                var activeObject = canvas.getActiveObject();
                if (activeObject) {
                    canvas.remove(activeObject);
                    canvas.renderAll();
                    updateLayersList();
                    $('#object-toolbar').hide();
                    saveState();
                }
            });
            
            $('#duplicate-object').click(function() {
                var activeObject = canvas.getActiveObject();
                if (activeObject) {
                    activeObject.clone(function(clone) {
                        clone.set({
                            left: activeObject.left + 10,
                            top: activeObject.top + 10,
                            name: activeObject.name + '-copy-' + Date.now()
                        });
                        canvas.add(clone);
                        canvas.setActiveObject(clone);
                        canvas.renderAll();
                        updateLayersList();
                        saveState();
                    });
                }
            });
            
            $('#flip-horizontal').click(function() {
                var activeObject = canvas.getActiveObject();
                if (activeObject) {
                    activeObject.set('flipX', !activeObject.flipX);
                    canvas.renderAll();
                    saveState();
                }
            });
            
            $('#flip-vertical').click(function() {
                var activeObject = canvas.getActiveObject();
                if (activeObject) {
                    activeObject.set('flipY', !activeObject.flipY);
                    canvas.renderAll();
                    saveState();
                }
            });
            
            // Font selection
            $('.font-option-btn').click(function() {
                $('.font-option-btn').removeClass('bg-blue-100 border-blue-300');
                $(this).addClass('bg-blue-100 border-blue-300');
                updateSelectedTextStyle();
            });
            
            // Text styling buttons
            $('#text-bold').click(function() {
                $(this).toggleClass('active');
                updateSelectedTextStyle();
            });
            
            $('#text-italic').click(function() {
                $(this).toggleClass('active');
                updateSelectedTextStyle();
            });
            
            $('#text-underline').click(function() {
                $(this).toggleClass('active');
                updateSelectedTextStyle();
            });
            
            // Text alignment buttons
            $('.text-align-btn').click(function() {
                $('.text-align-btn').removeClass('active');
                $(this).addClass('active');
                updateSelectedTextStyle();
            });
            
            // Stroke width
            $('#stroke-width').on('input', function() {
                $('#stroke-width-value').text($(this).val() + 'px');
                updateSelectedTextStyle();
            });
            
            // Text opacity
            $('#text-opacity').on('input', function() {
                $('#opacity-value').text($(this).val() + '%');
                updateSelectedTextStyle();
            });
            
            // Font size
            $('#font-size').change(function() {
                updateSelectedTextStyle();
            });
            
            // Text color
            $('#text-color').change(function() {
                updateSelectedTextStyle();
            });
            
            // Stroke color
            $('#stroke-color').change(function() {
                updateSelectedTextStyle();
            });
            
            // Update text style function
            function updateSelectedTextStyle() {
                var activeObject = canvas.getActiveObject();
                if (activeObject && activeObject.type === 'text') {
                    var activeFont = $('.font-option-btn.bg-blue-100').data('font') || 'Arial';
                    
                    activeObject.set({
                        fontFamily: activeFont,
                        fontSize: parseInt($('#font-size').val()),
                        fontWeight: $('#text-bold').hasClass('active') ? 'bold' : 'normal',
                        fontStyle: $('#text-italic').hasClass('active') ? 'italic' : 'normal',
                        underline: $('#text-underline').hasClass('active'),
                        textAlign: $('.text-align-btn.active').attr('id').replace('text-align-', ''),
                        fill: $('#text-color').val(),
                        stroke: $('#stroke-color').val(),
                        strokeWidth: parseInt($('#stroke-width').val()),
                        opacity: parseInt($('#text-opacity').val()) / 100
                    });
                    canvas.renderAll();
                    saveState();
                }
            }
            
            function getCurrentTextAlign() {
                var activeAlign = $('.text-align-btn.active').attr('id');
                return activeAlign ? activeAlign.replace('text-align-', '') : 'left';
            }
            
            // Add text to canvas
            $('#add-text').click(function() {
                var text = $('#text-input').val();
                if (text) {
                    var activeFont = $('.font-option-btn.bg-blue-100').data('font') || 'Arial';
                    
                    var textObj = new fabric.Text(text, {
                        left: 200,
                        top: 200,
                        fontFamily: activeFont,
                        fontSize: parseInt($('#font-size').val()),
                        fill: $('#text-color').val(),
                        fontWeight: $('#text-bold').hasClass('active') ? 'bold' : 'normal',
                        fontStyle: $('#text-italic').hasClass('active') ? 'italic' : 'normal',
                        underline: $('#text-underline').hasClass('active'),
                        textAlign: getCurrentTextAlign(),
                        stroke: $('#stroke-color').val(),
                        strokeWidth: parseInt($('#stroke-width').val()),
                        opacity: parseInt($('#text-opacity').val()) / 100,
                        name: 'text-' + Date.now()
                    });
                    canvas.add(textObj);
                    canvas.setActiveObject(textObj);
                    canvas.renderAll();
                    $('#text-input').val('');
                    updateLayersList();
                    saveState();
                }
            });
            
            // Color options for t-shirt
            $('.color-option').click(function() {
                var color = $(this).data('color');
                if (tshirtBase) {
                    currentColor = color;
                    loadTshirtView(currentView, color); 
                }
            });
                  
            // Object selection events
            canvas.on('selection:created', function() {
                $('#object-toolbar').show();
                updateActiveLayerHighlight();
                
                // Update text controls if a text object is selected
                var activeObject = canvas.getActiveObject();
                if (activeObject && activeObject.type === 'text') {
                    // Update font button
                    $('.font-option-btn').removeClass('bg-blue-100 border-blue-300');
                    $('.font-option-btn[data-font="' + activeObject.fontFamily + '"]').addClass('bg-blue-100 border-blue-300');
                    
                    // Update style buttons
                    $('#text-bold').toggleClass('active', activeObject.fontWeight === 'bold');
                    $('#text-italic').toggleClass('active', activeObject.fontStyle === 'italic');
                    $('#text-underline').toggleClass('active', activeObject.underline);
                    
                    // Update alignment buttons
                    $('.text-align-btn').removeClass('active');
                    $('#text-align-' + (activeObject.textAlign || 'left')).addClass('active');
                    
                    // Update color pickers
                    $('#text-color').val(activeObject.fill || '#000000');
                    $('#stroke-color').val(activeObject.stroke || '#000000');
                    $('#stroke-width').val(activeObject.strokeWidth || 0);
                    $('#stroke-width-value').text((activeObject.strokeWidth || 0) + 'px');
                    $('#text-opacity').val(Math.round((activeObject.opacity || 1) * 100));
                    $('#opacity-value').text(Math.round((activeObject.opacity || 1) * 100) + '%');
                    
                    // Update font size
                    $('#font-size').val(Math.round(activeObject.fontSize));
                }
            });
            
            canvas.on('selection:cleared', function() {
                $('#object-toolbar').hide();
                updateActiveLayerHighlight();
            });
            
            canvas.on('object:modified', function() {
                saveState();
                updateLayersList();
            });
            
            canvas.on('object:added', function() {
                saveState();
                updateLayersList();
            });
            
            canvas.on('object:removed', function() {
                saveState();
                updateLayersList();
            });
            
            // Update layers list
            function updateLayersList() {
                var layersHtml = '';
                var objects = canvas.getObjects();
                
                if (objects.length <= 1) { // Only tshirt base
                    layersHtml = '<p class="text-gray-500 text-sm">No objects in design yet</p>';
                } else {
                    // Reverse to show top layers first (skip tshirt base)
                    for (var i = objects.length - 1; i >= 0; i--) {
                        var obj = objects[i];
                        if (obj.name === 'tshirt-base') continue;
                        
                        var type = obj.type || 'object';
                        var name = type;
                        var preview = '';
                        
                        if (type === 'text') {
                            name = '"' + (obj.text.length > 15 ? obj.text.substring(0, 15) + '...' : obj.text) + '"';
                            preview = '<i class="fas fa-font text-gray-500 mr-2"></i>';
                        } else if (type === 'image') {
                            name = 'Image';
                            preview = '<i class="fas fa-image text-gray-500 mr-2"></i>';
                        } else if (type === 'rect' || type === 'circle' || type === 'triangle') {
                            name = type.charAt(0).toUpperCase() + type.slice(1);
                            preview = '<i class="fas fa-square text-gray-500 mr-2"></i>';
                        }
                        
                        var isActive = canvas.getActiveObject() === obj;
                        var activeClass = isActive ? 'bg-blue-100' : '';
                        
                        layersHtml += `
                            <div class="layer-item p-2 border-b cursor-pointer ${activeClass}" data-id="${obj.name}">
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center">
                                        ${preview}
                                        <span class="text-sm">${name}</span>
                                    </div>
                                    <i class="fas fa-eye text-gray-500"></i>
                                </div>
                            </div>
                        `;
                    }
                }
                
                $('#layers-list').html(layersHtml);
                
                // Make layers clickable
                $('.layer-item').click(function() {
                    var id = $(this).data('id');
                    var objects = canvas.getObjects();
                    for (var i = 0; i < objects.length; i++) {
                        if (objects[i].name === id) {
                            canvas.setActiveObject(objects[i]);
                            break;
                        }
                    }
                    canvas.renderAll();
                    $('.layer-item').removeClass('bg-blue-100');
                    $(this).addClass('bg-blue-100');
                });
            }
            
            function updateActiveLayerHighlight() {
                var activeObject = canvas.getActiveObject();
                $('.layer-item').removeClass('bg-blue-100');
                if (activeObject) {
                    $('.layer-item[data-id="' + activeObject.name + '"]').addClass('bg-blue-100');
                }
            }
            
            // Save state for undo/redo
            function saveState() {
                // Only keep states up to the current pointer
                if (undoPointer < undoStack.length - 1) {
                    undoStack = undoStack.slice(0, undoPointer + 1);
                }
                
                // Get current state for all views
                var state = {};
                ['front', 'back', 'left', 'right'].forEach(function(view) {
                    if (view === currentView) {
                        state[view] = canvas.toJSON();
                    } else if (undoPointer >= 0 && undoStack[undoPointer][view]) {
                        state[view] = undoStack[undoPointer][view];
                    }
                });
                
                undoStack.push(state);
                undoPointer++;
                
                // Limit undo stack size
                if (undoStack.length > 20) {
                    undoStack.shift();
                    undoPointer--;
                }
            }
            
            // Undo button
            $('#undo-btn').click(function() {
                if (undoPointer > 0) {
                    undoPointer--;
                    loadState(undoStack[undoPointer]);
                }
            });
            
            // Load state from undo stack
            function loadState(state) {
                if (state[currentView]) {
                    canvas.loadFromJSON(state[currentView], function() {
                        // After loading, we need to reset the tshirt base properties
                        var objects = canvas.getObjects();
                        objects.forEach(function(obj) {
                            if (obj.name === 'tshirt-base') {
                                obj.set({
                                    selectable: false,
                                    hasControls: false,
                                    lockMovementX: true,
                                    lockMovementY: true,
                                    lockRotation: true,
                                    lockScalingX: true,
                                    lockScalingY: true
                                });
                                tshirtBase = obj;
                            }
                        });
                        canvas.renderAll();
                        updateLayersList();
                    });
                }
            }
            
            // Save design
            $('#save-design').click(function() {
                var title = $('#design-title').val();
                if (!title) {
                    alert('Please enter a design title');
                    return;
                }
                
                // Get canvas as image for preview
                var dataURL = canvas.toDataURL({
                    format: 'png',
                    quality: 0.8
                });
                
                // Serialize canvas to JSON
                var designData = JSON.stringify(canvas.toJSON());
                
                // Save to server
                $.ajax({
                    url: '/design/save',
                    method: 'POST',
                    data: {
                        title: title,
                        design_data: designData,
                        preview_image: dataURL,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Design saved successfully!');
                        $('#design-title').val('');
                    },
                    error: function(xhr) {
                        alert('Error saving design: ' + xhr.responseText);
                    }
                });
            });
            
            // Export design as image
            $('#export-design').click(function() {
                var link = document.createElement('a');
                link.download = 'tshirt-design.png';
                link.href = canvas.toDataURL({
                    format: 'png',
                    quality: 0.8
                });
                link.click();
            });
            
            // Proceed button
            $('#proceed-btn').click(function() {
                // In a real app, this would redirect to checkout
                alert('Added to cart!');
            });
            
            // File menu button
            $('#file-menu-btn').click(function() {
                $('#designs-modal').removeClass('hidden');
                loadSavedDesigns();
            });
            
            // Close modal
            $('#close-modal').click(function() {
                $('#designs-modal').addClass('hidden');
            });
            
            // Load saved designs
            function loadSavedDesigns() {
                $.get('/design/list', function(data) {
                    var designsHtml = '';
                    if (data.length === 0) {
                        designsHtml = '<p class="text-gray-500">No saved designs yet</p>';
                    } else {
                        data.forEach(function(design) {
                            designsHtml += `
                                <div class="border rounded p-2">
                                    <img src="${design.preview_image || '/images/default-preview.png'}" alt="${design.title}" class="w-full h-32 object-contain mb-2">
                                    <h4 class="font-semibold truncate">${design.title}</h4>
                                    <p class="text-sm text-gray-500">${new Date(design.created_at).toLocaleDateString()}</p>
                                    <button class="load-design-btn w-full mt-2 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm py-1 px-2 rounded" data-id="${design.id}">
                                        Load Design
                                    </button>
                                </div>
                            `;
                        });
                    }
                    $('#saved-designs-container').html(designsHtml);
                    
                    // Bind load design buttons
                    $('.load-design-btn').click(function() {
                        var id = $(this).data('id');
                        loadDesignFromServer(id);
                    });
                });
            }
            
            // Load design from server
            function loadDesignFromServer(id) {
                $.get('/design/load/' + id, function(data) {
                    // Save current view state
                    var currentState = undoStack[undoPointer] || {};
                    currentState[currentView] = canvas.toJSON();
                    
                    // Load the design
                    canvas.loadFromJSON(data.design_data, function() {
                        // After loading, we need to reset the tshirt base properties
                        var objects = canvas.getObjects();
                        objects.forEach(function(obj) {
                            if (obj.name === 'tshirt-base') {
                                obj.set({
                                    selectable: false,
                                    hasControls: false,
                                    lockMovementX: true,
                                    lockMovementY: true,
                                    lockRotation: true,
                                    lockScalingX: true,
                                    lockScalingY: true
                                });
                                tshirtBase = obj;
                            }
                        });
                        
                        // Update preview image
                        $('#design-preview').attr('src', data.preview_image || '/images/default-preview.png');
                        
                        // Update design title
                        $('#design-title').val(data.title);
                        
                        canvas.renderAll();
                        updateLayersList();
                        
                        // Add to undo stack
                        var newState = {};
                        newState[currentView] = canvas.toJSON();
                        undoStack.push(newState);
                        undoPointer++;
                        
                        // Close modal
                        $('#designs-modal').addClass('hidden');
                    });
                });
            }
            
            // Load cliparts from server (simulated in this example)
            function loadCliparts() {
                // In a real app, you would load these from your database
                var cliparts = [
                    { id: 1, name: 'Design 1', url: '/images/design1.png', category: 'cliparts' },
                    { id: 2, name: 'Design 2', url: '/images/design2.png', category: 'cliparts' },
                    { id: 3, name: 'Design 3', url: '/images/design3.png', category: 'templates' },
                    { id: 4, name: 'Design 4', url: '/images/design4.png', category: 'images' },
                    { id: 5, name: 'Design 5', url: '/images/design5.png', category: 'cliparts' },
                    { id: 6, name: 'Design 6', url: '/images/design6.png', category: 'shapes' },
                    { id: 7, name: 'Design 7', url: '/images/design7.png', category: 'cliparts' },
                    { id: 8, name: 'Design 8', url: '/images/design8.png', category: 'templates' },
                    { id: 9, name: 'Design 9', url: '/images/design9.png', category: 'images' },
                    { id: 10, name: 'Design 10', url: '/images/design10.png', category: 'cliparts' },
                    { id: 11, name: 'Design 11', url: '/images/design11.png', category: 'shapes' },
                    { id: 12, name: 'Design 12', url: '/images/design12.png', category: 'cliparts' }
                ];
                
                renderCliparts(cliparts);
                
                // Category filter
                $('.category-btn').click(function() {
                    $('.category-btn').removeClass('active');
                    $(this).addClass('active');
                    var category = $(this).data('category');
                    if (category === 'all') {
                        renderCliparts(cliparts);
                    } else {
                        var filtered = cliparts.filter(function(clipart) {
                            return clipart.category === category;
                        });
                        renderCliparts(filtered);
                    }
                });
                
                // Search filter
                $('#search-cliparts').on('input', function() {
                    var searchTerm = $(this).val().toLowerCase();
                    if (searchTerm === '') {
                        renderCliparts(cliparts);
                    } else {
                        var filtered = cliparts.filter(function(clipart) {
                            return clipart.name.toLowerCase().includes(searchTerm);
                        });
                        renderCliparts(filtered);
                    }
                });
            }
            
            function renderCliparts(cliparts) {
                var html = '';
                cliparts.forEach(function(clipart) {
                    html += `
                        <div class="draggable-clipart" data-type="clipart" data-src="${clipart.url}">
                            <img src="${clipart.url}" alt="${clipart.name}" class="w-full border rounded">
                            <p class="text-xs text-center mt-1 truncate">${clipart.name}</p>
                        </div>
                    `;
                });
                $('#cliparts-grid').html(html);
                
                // Make cliparts draggable
                $('.draggable-clipart').on('mousedown', function(e) {
                    var type = $(this).data('type');
                    var src = $(this).data('src');
                    
                    if (type === 'clipart') {
                        fabric.Image.fromURL(src, function(img) {
                            img.set({
                                left: canvas.width / 2,
                                top: canvas.height / 2,
                                scaleX: 0.5,
                                scaleY: 0.5,
                                originX: 'center',
                                originY: 'center',
                                name: 'clipart-' + Date.now()
                            });
                            canvas.add(img);
                            canvas.setActiveObject(img);
                            canvas.renderAll();
                            updateLayersList();
                            saveState();
                        });
                    }
                });
            }
            
            // Initialize with Arial font selected
            $('.font-option-btn[data-font="Arial"]').addClass('bg-blue-100 border-blue-300');
        });
    </script>
</body>
</html>