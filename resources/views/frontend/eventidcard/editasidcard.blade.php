@extends('frontend.layout.applayout')
@section('title', 'Event ID Card Editor')
@section('content')
@php
  //echo '<pre>'; print_r($designcard); die;
  $editorBackground = $idCardData->file_path;
  $editorBackgroundUrl = $editorBackground
      ? (preg_match('/^https?:\\/\\//', $editorBackground)
          ? $editorBackground
          : asset('storage/' . $editorBackground))
      : '';


      $height = $idCardData->height;
      $width  = $idCardData->width;

      //$dpi = 96;

     // $height = round(($heightMm / 25.4) * $dpi);
     // $width  = round(($widthMm / 25.4) * $dpi);
  @endphp
<style>
  :root{
    --maroon:#9e1b32;
    --maroon-dark:#7a1526;
    --gold:#e8b84b;
    --ink:#1f2430;
    --muted:#6b7280;
    --line:#e5e7eb;
    --panel-bg:#ffffff;
    --bg:#f2f3f5;
    --accent:#2f6fed;
  }
  *{box-sizing:border-box;}
  body{
    margin:0;
    font-family:'Segoe UI',Arial,sans-serif;
    background:var(--bg);
    color:var(--ink);
  }
  .topbar{
    background:var(--maroon);
    color:#fff;
    padding:14px 24px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    box-shadow:0 2px 8px rgba(0,0,0,.15);
  }
  .topbar h1{
    font-size:17px;
    margin:0;
    font-weight:700;
    letter-spacing:.2px;
  }
  .topbar .sub{
    font-size:12px;
    opacity:.85;
    margin-top:2px;
    font-weight:400;
  }
  .topbar button{
    background:var(--gold);
    color:#3a2a00;
    border:none;
    padding:9px 16px;
    border-radius:6px;
    font-weight:700;
    font-size:13px;
    cursor:pointer;
  }
  .topbar button:hover{filter:brightness(1.05);}

  .editor{
    display:flex;
    gap:24px;
    padding:24px;
    align-items:flex-start;
    flex-wrap:wrap;
  }

  .controls{
    order:2;
    width:460px;
    background:var(--panel-bg);
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,.08);
    padding:6px 0 16px;
  }
  .group{
    border-bottom:1px solid var(--line);
    padding:14px 18px;
  }
  .group:last-child{border-bottom:none;}

  .group-title{
    display:flex;
    align-items:center;
    justify-content:space-between;
    cursor:pointer;
    user-select:none;
  }
  .group-title h3{
    margin:0;
    font-size:13.5px;
    font-weight:700;
    color:var(--maroon-dark);
  }
  .group-title .chev{
    font-size:12px;
    color:var(--muted);
    transition:transform .15s;
  }
  .group.collapsed .chev{transform:rotate(-90deg);}
  .group-body{
    margin-top:12px;
    display:flex;
    flex-direction:column;
    gap:10px;
  }
  .group.collapsed .group-body{display:none;}

  .field label{
    display:block;
    font-size:11px;
    font-weight:600;
    color:var(--muted);
    margin-bottom:4px;
    text-transform:uppercase;
    letter-spacing:.3px;
  }
  .field input[type="text"],
  .field input[type="number"],
  .field select{
    width:100%;
    padding:7px 8px;
    border:1px solid #d5d8dd;
    border-radius:5px;
    font-size:13px;
    font-family:inherit;
  }
  .field input[type="color"]{
    width:44px;
    height:30px;
    padding:2px;
    border:1px solid #d5d8dd;
    border-radius:5px;
    cursor:pointer;
  }
  .row2{display:flex;gap:8px;}
  .row2 .field{flex:1;}
  .row4{display:flex;gap:8px;flex-wrap:wrap;}
  .row4 .field{flex:1;min-width:70px;}

  .filebtn{
    display:inline-block;
    width:100%;
    text-align:center;
    padding:8px;
    border:1px dashed #b9bec7;
    border-radius:6px;
    font-size:12.5px;
    color:var(--muted);
    cursor:pointer;
    background:#fafbfc;
  }
  .filebtn:hover{border-color:var(--accent);color:var(--accent);}
  .filebtn input{display:none;}

  .reset-link{
    font-size:11px;
    color:var(--accent);
    cursor:pointer;
    text-decoration:underline;
    background:none;
    border:none;
    padding:0;
  }

  .switch{
    position:relative;
    display:inline-block;
    width:34px;
    height:19px;
    flex-shrink:0;
  }
  .switch input{opacity:0;width:0;height:0;}
  .switch .slider{
    position:absolute;
    cursor:pointer;
    top:0;left:0;right:0;bottom:0;
    background:#ccced3;
    border-radius:19px;
    transition:.15s;
  }
  .switch .slider:before{
    position:absolute;
    content:"";
    height:14px;
    width:14px;
    left:2.5px;
    bottom:2.5px;
    background:#fff;
    border-radius:50%;
    transition:.15s;
    box-shadow:0 1px 2px rgba(0,0,0,.3);
  }
  .switch input:checked + .slider{background:var(--maroon);}
  .switch input:checked + .slider:before{transform:translateX(15px);}

  .group-title-right{display:flex;align-items:center;gap:10px;}
  .group.field-off .group-title h3{color:var(--muted);}

  .toggle-all-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:12px 18px;
    border-bottom:1px solid var(--line);
    background:#fafbfc;
  }
  .toggle-all-row span{font-size:12.5px;font-weight:700;color:var(--maroon-dark);}
  .toggle-all-row .links{display:flex;gap:10px;}
  .toggle-all-row .links button{
    font-size:11px;
    color:var(--accent);
    background:none;
    border:none;
    cursor:pointer;
    text-decoration:underline;
    padding:0;
  }

  .preview-wrap{
    order:1;
    flex:1;
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:16px;
    position:sticky;
    top:24px;
    align-self:flex-start;
  }
  .zoom-controls{display:flex;align-items:center;gap:10px;font-size:12px;color:var(--muted);}

  .card-stage{padding:40px;border-radius:12px;}

  .id-card{
    position:relative;
    background-image: url('{{ $editorBackgroundUrl }}');
    background-color:#e9eaed;
    background-size:100% 100%;
    background-repeat:no-repeat;
    background-position:center;
    overflow:hidden;
    box-shadow:0 8px 24px rgba(0,0,0,.25);
    border-radius:6px;
    transform-origin:top center;
  }

  .el{position:absolute;cursor:move;outline:1px dashed transparent;z-index:2;}
  .el:hover{outline-color:rgba(47,111,237,.6);}
  .el.dragging{outline-color:var(--accent);z-index:50;}

  .el-photo{object-fit:cover;border:3px solid var(--maroon);background:#eee;}
  .el-text{display:block;white-space:normal;word-break:break-word;overflow-wrap:anywhere;max-width:calc(100% - 20px);line-height:1.25;}
  .el-tabledata{
    position:absolute;z-index:3;display:block;background:transparent;border:none;
    color:var(--ink);font-size:12px;line-height:1.25;overflow:auto;pointer-events:none;
    width:auto;min-width:0;max-width:none;box-sizing:border-box;
  }
  .el-tabledata table{width:100%;min-width:100%;height:100%;border-collapse:collapse;}
  .el-tabledata th,.el-tabledata td{border:1px solid rgba(31,36,48,0.35);padding:3px 5px;text-align:left;vertical-align:top;}
  .el-qr{object-fit:contain;}
  .el-logo{object-fit:contain;background:transparent;}
  .el-sign{object-fit:contain;background:transparent;}

  .background-area{position:absolute;cursor:move;box-sizing:border-box;outline:1px dashed transparent;user-select:none;}
  .background-area:hover{outline-color:rgba(47,111,237,.75);}
  .background-area.dragging{outline:2px dashed var(--accent);}

  .hint{font-size:12px;color:var(--muted);text-align:center;max-width:700px;}

  .floating-toolbar{
    position:fixed;left:42%;bottom:22px;transform:translateX(-50%);z-index:1000;
    display:flex;align-items:center;gap:6px;background:#fff;padding:10px 10px;
    border-radius:40px;box-shadow:0 6px 22px rgba(0,0,0,.22);
  }

  .field-css{
    width:100%;min-height:44px;padding:6px 8px;border:1px solid #d5d8dd;border-radius:6px;
    font-size:12px;font-family:Consolas,monospace;background:#ffffff;resize:vertical;
  }

  ::-webkit-scrollbar{width:8px;}
  ::-webkit-scrollbar-thumb{background:#c9ccd1;border-radius:4px;}

  @media (max-width: 991px){
    .editor{padding:16px;gap:16px;}
    .controls{width:100%;}
    .preview-wrap{width:100%;position:static;top:auto;}
  }
  @media (max-width: 767px){
    html, body{overflow-x:hidden;}
    .id-editor-header-row{flex-wrap:wrap;gap:10px;}
    .id-editor-header-actions{flex-wrap:wrap;width:100%;justify-content:flex-start;gap:6px;}
    .id-editor-header-actions .btn{margin:0 !important;font-size:12px;padding:6px 10px;}
    .editor{padding:12px;gap:14px;}
    .controls{border-radius:8px;}
    .group{padding:12px 14px;}
    .row4{gap:6px;}
    .row4 .field{min-width:64px;}
    .card-stage{padding:20px;max-width:100%;overflow-x:auto;}
    .zoom-controls{flex-wrap:wrap;justify-content:center;text-align:center;}
    .hint{max-width:100%;padding:0 8px;}
    .floating-toolbar{left:50%;bottom:12px;max-width:94vw;flex-wrap:wrap;justify-content:center;padding:8px;gap:6px;}
    .floating-toolbar .btn{font-size:12px;padding:6px 10px;margin:0 !important;}
  }
  @media (max-width: 420px){
    .id-editor-header-actions .btn{font-size:11px;padding:5px 8px;}
    .card-stage{padding:12px;}
    .row4 .field{min-width:56px;}
    .field input[type="text"],
    .field input[type="number"],
    .field select{font-size:16px;}
  }

  .btn{border:none;border-radius:6px;padding:8px 14px;font-weight:700;font-size:13px;cursor:pointer;}
  .btn-primary{background:var(--gold);color:#3a2a00;}
  .btn-primary:hover{filter:brightness(1.05);}
  .card{background:#fff;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,.06);}
  .card-header{padding:16px 20px;border-bottom:1px solid var(--line);}
  .card-body{padding:0;}
  .d-flex{display:flex;}
  .align-items-center{align-items:center;}
  .justify-content-between{justify-content:space-between;}
  .w-100{width:100%;}
  .mb-0{margin:0;}
  .mt-4{margin-top:24px;}
  .mx-1{margin-left:4px;margin-right:4px;}
  .mx-2{margin-left:8px;margin-right:8px;}
  .container-fluid{padding:0 16px;}
</style>
</head>
<body>

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="card mt-4">
        <div class="card-header">
          <div class="d-flex align-items-center">
            <div class="d-flex align-items-center justify-content-between w-100 id-editor-header-row">
              <div>
                <h4 class="mb-0">Event ID Card Editor</h4>
                <div class="sub">drag fields on the card or use the controls</div>
              </div>
              <div class="d-flex align-items-center id-editor-header-actions">

                <!-- CARD SIZE INPUTS (replaces horizontal / vertical dropdown) -->
                <div class="d-flex align-items-center" style="gap:8px;margin-right:10px;">
                  <input type="number" id="cardWidthInput" placeholder="Width (mm)" value="{{ $width}}"
                         style="width:90px;padding:6px 8px;border:1px solid #d5d8dd;border-radius:5px;font-size:13px;">
                  <span>×</span>
                  <input type="number" id="cardHeightInput" placeholder="Height (mm)" value="{{ $height}}"
                         style="width:90px;padding:6px 8px;border:1px solid #d5d8dd;border-radius:5px;font-size:13px;">
                  <button type="button" id="applyCardSizeBtn" class="btn"
                          style="background:var(--gold);color:#3a2a00;">Apply Size</button>
                </div>

                <button type="button" id="saveLayoutBtn" class="btn btn-primary mx-2">💾 Save ID Card</button>
               
              </div>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="editor">

            <!-- ===================== CONTROLS ===================== -->
            <div class="controls" id="controlsPanel">

              <div class="toggle-all-row">
                <span>Field visibility</span>
                <div class="links">
                  <button type="button" id="showAllBtn">Show all</button>
                  <button type="button" id="hideAllBtn">Hide all</button>
                </div>
              </div>

              <!-- CARD BACKGROUND -->
             <div class="group">
                  <div class="group-title">
                      <h3>Card Background</h3>
                      <span class="chev">▾</span>
                  </div>

                  <div class="group-body">

                      <label class="filebtn">
                          Click to upload background image

                          <input
                              type="file"
                              id="bgUpload"
                              accept="image/jpeg,image/png,image/webp"
                          >
                      </label>

                      <div
                          id="bgUploadStatus"
                          style="font-size:11px;color:var(--muted);margin-top:8px;"
                      ></div>

                  </div>
              </div>

              <!-- BACKGROUND AREAS -->
              <div class="group" id="backgroundAreasGroup">
                <div class="group-title">
                  <h3>Background Areas</h3>
                  <div class="group-title-right">
                    <button type="button" id="addBackgroundAreaBtn"
                      style="background:var(--maroon);color:#fff;border:none;border-radius:5px;padding:5px 9px;font-size:11px;font-weight:700;cursor:pointer;">+ Add Area</button>
                    <span class="chev">▾</span>
                  </div>
                </div>
                <div class="group-body" id="backgroundAreasContainer">
                  <div id="backgroundAreasEmpty" style="font-size:11px;color:var(--muted);">
                    Add a rectangle over the background and control its X, Y, width, height and color.
                  </div>
                </div>
              </div>

              <!-- LAYOUT DATA (table / inline) -->
              <div class="group">
                <div class="group-title"><h3>Layout Data</h3><span class="chev">▾</span></div>
                <div class="group-body">
                  <div style="display:flex;gap:8px;margin-bottom:12px;border-bottom:1px solid var(--line);padding-bottom:10px;">
                    <button type="button" class="layout-tab-btn active" data-tab="table-tab"
                      style="flex:1;border:1px solid #d5d8dd;background:var(--maroon);color:#fff;border-radius:6px;padding:7px 10px;font-size:12px;font-weight:700;cursor:pointer;">Table</button>
                    <button type="button" class="layout-tab-btn" data-tab="inline-tab"
                      style="flex:1;border:1px solid #d5d8dd;background:#f8fafc;color:var(--muted);border-radius:6px;padding:7px 10px;font-size:12px;font-weight:700;cursor:pointer;">Inline</button>
                  </div>

                  <div id="table-tab" class="layout-tab-panel" style="display:block;">
                    <div class="field">
                      <label>Quick Build</label>
                      <div class="row4" style="align-items:flex-end;">
                        <div class="field" style="flex:0 0 64px;"><label>Rows</label><input type="number" id="tableBuildRows" min="1" max="20" value="3"></div>
                        <div class="field" style="flex:0 0 64px;"><label>Cols</label><input type="number" id="tableBuildCols" min="1" max="10" value="2"></div>
                        <div class="field" style="flex:1;"><label>&nbsp;</label><button type="button" id="tableNewBtn" class="filebtn" style="border-style:solid;">＋ New Table</button></div>
                        <div class="field" style="flex:1;"><label>&nbsp;</label><button type="button" id="tableLoadBtn" class="filebtn" style="border-style:solid;">↺ Load From HTML</button></div>
                      </div>
                      <button type="button" id="tablePresetStudentBtn" class="filebtn" style="border-style:solid;margin-top:8px;">🎓 Insert Student Info Table</button>
                    </div>

                    <div class="field">
                      <label>Grid Controls</label>
                      <div class="row4">
                        <button type="button" id="tableAddRowBtn" class="filebtn" style="flex:1;border-style:solid;">+ Row</button>
                        <button type="button" id="tableDelRowBtn" class="filebtn" style="flex:1;border-style:solid;">− Row</button>
                        <button type="button" id="tableAddColBtn" class="filebtn" style="flex:1;border-style:solid;">+ Column</button>
                        <button type="button" id="tableDelColBtn" class="filebtn" style="flex:1;border-style:solid;">− Column</button>
                      </div>
                    </div>

                    <div class="field">
                      <label>Style</label>
                      <div class="row4">
                        <div class="field"><label>Border Px</label><input type="number" id="tableBorderWidth" min="0" max="10" value="1"></div>
                        <div class="field"><label>Border Color</label><input type="color" id="tableBorderColor" value="#1f2430"></div>
                        <div class="field"><label>Font Px</label><input type="number" id="tableFontSize" min="6" max="30" value="12"></div>
                      </div>
                      <div class="row4" style="margin-top:8px;">
                        <div class="field"><label>Padding Top</label><input type="number" id="tablePaddingTop" min="0" max="60" value="3"></div>
                        <div class="field"><label>Padding Right</label><input type="number" id="tablePaddingRight" min="0" max="60" value="3"></div>
                        <div class="field"><label>Padding Bottom</label><input type="number" id="tablePaddingBottom" min="0" max="60" value="3"></div>
                        <div class="field"><label>Padding Left</label><input type="number" id="tablePaddingLeft" min="0" max="60" value="3"></div>
                      </div>
                      <div class="row4" style="margin-top:8px;">
                        <div class="field"><label>Margin Top</label><input type="number" id="tableMarginTop" min="0" max="60" value="0"></div>
                        <div class="field"><label>Margin Right</label><input type="number" id="tableMarginRight" min="0" max="60" value="0"></div>
                        <div class="field"><label>Margin Bottom</label><input type="number" id="tableMarginBottom" min="0" max="60" value="0"></div>
                        <div class="field"><label>Margin Left</label><input type="number" id="tableMarginLeft" min="0" max="60" value="0"></div>
                      </div>
                      <div class="row2" style="margin-top:8px;align-items:center;">
                        <div class="field">
                          <label>Text Align</label>
                          <select id="tableTextAlign">
                            <option value="left">Left</option>
                            <option value="center">Center</option>
                            <option value="right">Right</option>
                          </select>
                        </div>
                        <div class="field" style="display:flex;align-items:center;gap:8px;flex:0 0 auto;">
                          <label style="margin:0;text-transform:none;">Header Row</label>
                          <label class="switch"><input type="checkbox" id="tableHeaderToggle" checked><span class="slider"></span></label>
                        </div>
                      </div>
                    </div>

                    <div class="field">
                      <label>Visual Editor <span style="font-weight:400;text-transform:none;color:var(--muted);">(click a block to select it)</span></label>
                      <div id="tableGridEditor" style="border:1px dashed #d5d8dd;border-radius:6px;padding:8px;overflow:auto;max-height:220px;background:#c1c3c4;"></div>
                    </div>

                    <div class="field">
                      <label for="tabledataTextarea">Raw HTML / CSS (auto-synced with the visual editor above)</label>
                      <textarea id="tabledataTextarea" rows="6" placeholder="Enter HTML table layout and CSS here" style="resize:both;width:100%;padding:7px 8px;border:1px solid #d5d8dd;border-radius:5px;font-size:12px;font-family:Consolas,monospace;">
                        
                        {{@$designcard->layout['tabledata']}}
                      </textarea>
                    </div>

                    <div class="row4">
                      <div class="field"><label>Left</label><input type="number" id="tableLeft" value="{{@$designcard->layout['tablePosition']['left'] ?? '15'}}"></div>
                      <div class="field"><label>Top</label><input type="number" id="tableTop" value="{{@$designcard->layout['tablePosition']['top'] ?? '15'}}"></div>
                      <div class="field"><label>Width</label><input type="number" id="tableWidth" value="{{@$designcard->layout['tablePosition']['width'] ?? '150'}}"></div>
                      <div class="field"><label>Height</label><input type="number" id="tableHeight" value="{{@$designcard->layout['tablePosition']['height'] ?? '150'}}"></div>
                    </div>
                  </div>

                  <div id="inline-tab" class="layout-tab-panel" style="display:none;">
                    <div class="field">
                      <label for="usevalTextarea">Inline Value</label>
                      <textarea id="usevalTextarea" rows="8" placeholder="Enter inline layout / value here" style="width:100%;padding:7px 8px;border:1px solid #d5d8dd;border-radius:5px;font-size:13px;"></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Event LOGO -->
              <div class="group">
                  <div class="group-title"><h3>Event Logo</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="logoToggle"  @if(isset($designcard->layout['fields']['logo'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                  <div class="group-body">
                      <label class="filebtn">Click to upload logo
                      <input type="file" id="logoUpload" accept="image/*">
                      </label>
                      <div class="row4">
                      <div class="field"><label>X</label><input type="number" id="logoX" value="{{ @$designcard->layout['fields']['logo']['x'] ?? 30 }}"></div>
                      <div class="field"><label>Y</label><input type="number" id="logoY" value="{{ @$designcard->layout['fields']['logo']['y'] ?? 20 }}"></div>
                      <div class="field"><label>W</label><input type="number" id="logoW" value="{{ @$designcard->layout['fields']['logo']['width'] ?? 100 }}"></div>
                      <div class="field"><label>H</label><input type="number" id="logoH" value="{{ $designcard->layout['fields']['logo']['height'] ?? 100 }}"></div>
                      </div>
                  </div>
              </div>

              <!-- Event NAME -->
              <div class="group">
                <div class="group-title"><h3>Event Name</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="schoolNameToggle"  @if(isset($designcard->layout['fields']['schoolName'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                <div class="group-body">
                  <div class="field"><label>Text</label><input type="text" id="schoolNameText" value=" {{@$designcard->layout['fields']['schoolName']['text'] ?? 'New Event'}}"></div>
                            <div class="row4">
                                <div class="field"><label>X</label><input type="number" id="schoolNameX" value="{{ $designcard->layout['fields']['schoolName']['x'] ?? 30 }}"></div>
                                <div class="field"><label>Y</label><input type="number" id="schoolNameY" value="{{ $designcard->layout['fields']['schoolName']['y'] ?? 30 }}"></div>
                                <div class="field"><label>Size</label><input type="number" id="schoolNameSize" value="{{ $designcard->layout['fields']['schoolName']['fontSize'] ?? 15 }}"></div>
                            </div>
                            <div class="row2">
                            <div class="field"><label>Color</label><input type="color" id="schoolNameColor" value="{{ $designcard->layout['fields']['schoolName']['color'] ?? '#9e1b32' }}"></div>
                            <div class="field"><label>Weight</label>
                                <select id="schoolNameWeight">
                                <option value="700" @if(isset($designcard->layout['fields']['schoolName']['fontWeight']) && $designcard->layout['fields']['schoolName']['fontWeight'] == 700) selected @endif>Bold</option>
                                <option value="400" @if(isset($designcard->layout['fields']['schoolName']['fontWeight']) && $designcard->layout['fields']['schoolName']['fontWeight'] == 400) selected @endif>Normal</option>
                                </select>
                            </div>
                  </div>
                </div>
              </div>

                     <!-- ADDRESS -->
                        <div class="group">
                        <div class="group-title"><h3>Event Address</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="addressToggle" @if(isset($designcard->layout['fields']['address'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <div class="field"><label>Text</label><input type="text" id="addressText" value="123 Education Lane, Varanasi, UP - 221001"></div>
                            <div class="row4">
                            <div class="field"><label>X</label><input type="number" id="addressX" value="{{ $designcard->layout['fields']['address']['x'] ?? 30 }}"></div>
                            <div class="field"><label>Y</label><input type="number" id="addressY" value="{{ $designcard->layout['fields']['address']['y'] ?? 30 }}"></div>
                            <div class="field"><label>Size</label><input type="number" id="addressSize" value="{{ $designcard->layout['fields']['address']['fontSize'] ?? 15 }}"></div>
                            </div>
                            <div class="row2">
                            <div class="field"><label>Color</label><input type="color" id="addressColor" value="@php echo $designcard->layout['fields']['address']['color'] ?? '#1f2430'; @endphp"></div>
                            <div class="field"><label>Weight</label>
                                <select id="addressWeight">
                                <option value="700" @php echo (isset($designcard->layout['fields']['address']['fontWeight']) && $designcard->layout['fields']['address']['fontWeight'] == 700) ? 'selected' : ''; @endphp>Bold</option>
                                <option value="400" @php echo (isset($designcard->layout['fields']['address']['fontWeight']) && $designcard->layout['fields']['address']['fontWeight'] == 400) ? 'selected' : ''; @endphp>Normal</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- STUDENT ADDRESS -->
                        <div class="group">
                        <div class="group-title"><h3>Participant Address</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="studentAddressToggle" @if(isset($designcard->layout['fields']['studentAddress'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <div class="field"><label>Text</label><input type="text" id="studentAddressText" value="{{ $designcard->layout['fields']['studentAddress']['text'] ?? 'Address: 24, Green Park, Varanasi, UP - 221001' }}"></div>
                            <div class="row4">
                            <div class="field"><label>X</label><input type="number" id="studentAddressX" value="{{ $designcard->layout['fields']['studentAddress']['x'] ?? 30 }}"></div>
                            <div class="field"><label>Y</label><input type="number" id="studentAddressY" value="{{ $designcard->layout['fields']['studentAddress']['y'] ?? 58 }}"></div>
                            <div class="field"><label>Size</label><input type="number" id="studentAddressSize" value="{{ $designcard->layout['fields']['studentAddress']['fontSize'] ?? 14 }}"></div>
                            </div>
                            <div class="row2">
                            <div class="field"><label>Color</label><input type="color" id="studentAddressColor" value="@php echo $designcard->layout['fields']['studentAddress']['color'] ?? '#1f2430'; @endphp"></div>
                            <div class="field"><label>Weight</label>
                                <select id="studentAddressWeight">
                                <option value="700" @php echo (isset($designcard->layout['fields']['studentAddress']['fontWeight']) && $designcard->layout['fields']['studentAddress']['fontWeight'] == 700) ? 'selected' : ''; @endphp>Bold</option>
                                <option value="400" @php echo (isset($designcard->layout['fields']['studentAddress']['fontWeight']) && $designcard->layout['fields']['studentAddress']['fontWeight'] == 400) ? 'selected' : ''; @endphp>Normal</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- SESSION -->
                        <div class="group">
                        <div class="group-title"><h3>Date to From</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="sessionToggle" @if(isset($designcard->layout['fields']['session'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <div class="field"><label>Text</label><input type="text" id="sessionText" value="2026-2027"></div>
                            <div class="row4">
                            <div class="field"><label>X</label><input type="number" id="sessionX" value="@php echo $designcard->layout['fields']['session']['x'] ?? 30; @endphp"></div>
                            <div class="field"><label>Y</label><input type="number" id="sessionY" value="@php echo $designcard->layout['fields']['session']['y'] ?? 30; @endphp"></div>
                            <div class="field"><label>Size</label><input type="number" id="sessionSize" value="@php echo $designcard->layout['fields']['session']['fontSize'] ?? 13; @endphp"></div>
                            </div>
                            <div class="row2">
                            <div class="field"><label>Color</label><input type="color" id="sessionColor" value="@php echo $designcard->layout['fields']['session']['color'] ?? '#1f2430'; @endphp"></div>
                            <div class="field"><label>Weight</label>
                                <select id="sessionWeight">
                                <option value="700" @php echo (isset($designcard->layout['fields']['session']['fontWeight']) && $designcard->layout['fields']['session']['fontWeight'] == 700) ? 'selected' : ''; @endphp>Bold</option>
                                <option value="400" @php echo (isset($designcard->layout['fields']['session']['fontWeight']) && $designcard->layout['fields']['session']['fontWeight'] == 400) ? 'selected' : ''; @endphp>Normal</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- STUDENT PHOTO -->
                        <div class="group">
                        <div class="group-title"><h3>Participant Photo</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="photoToggle" @if(isset($designcard->layout['fields']['photo'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <label class="filebtn">Click to upload photo
                            <input type="file" id="photoUpload" accept="image/*">
                            </label>
                            <div class="row4">
                            <div class="field"><label>X</label><input type="number" id="photoX" value="@php echo $designcard->layout['fields']['photo']['x'] ?? 55; @endphp"></div>
                            <div class="field"><label>Y</label><input type="number" id="photoY" value="@php echo $designcard->layout['fields']['photo']['y'] ?? 80; @endphp"></div>
                            <div class="field"><label>W</label><input type="number" id="photoW" value="@php echo $designcard->layout['fields']['photo']['width'] ?? 150; @endphp"></div>
                            <div class="field"><label>H</label><input type="number" id="photoH" value="@php echo $designcard->layout['fields']['photo']['height'] ?? 150; @endphp"></div>
                            </div>
                            <div class="field"><label>Radius</label><input type="number" id="photoRadius" value="@php echo $designcard->layout['fields']['photo']['borderRadius'] ?? 0; @endphp"></div>
                        </div>
                        </div>

                        <!-- STUDENT NAME -->
                        <div class="group">
                        <div class="group-title"><h3>Participant Name</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="nameToggle" @if(isset($designcard->layout['fields']['name'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <div class="field"><label>Text</label><input type="text" id="nameText" value="AARAV SHARMA"></div>
                            <div class="row4">
                            <div class="field"><label>X</label><input type="number" id="nameX" value="@php echo $designcard->layout['fields']['name']['x'] ?? 30; @endphp"></div>
                            <div class="field"><label>Y</label><input type="number" id="nameY" value="@php echo $designcard->layout['fields']['name']['y'] ?? 40; @endphp"></div>
                            <div class="field"><label>Size</label><input type="number" id="nameSize" value="@php echo $designcard->layout['fields']['name']['fontSize'] ?? 24; @endphp"></div>
                            </div>
                            <div class="row2">
                            <div class="field"><label>Color</label><input type="color" id="nameColor" value="@php echo $designcard->layout['fields']['name']['color'] ?? '#16009f'; @endphp"></div>
                            <div class="field"><label>Weight</label>
                                <select id="nameWeight">
                                <option value="700" @php echo (isset($designcard->layout['fields']['name']['fontWeight']) && $designcard->layout['fields']['name']['fontWeight'] == 700) ? 'selected' : ''; @endphp>Bold</option>
                                <option value="400" @php echo (isset($designcard->layout['fields']['name']['fontWeight']) && $designcard->layout['fields']['name']['fontWeight'] == 400) ? 'selected' : ''; @endphp>Normal</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- FATHER'S NAME -->
                        <div class="group">
                        <div class="group-title"><h3>Father's Name</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="fatherToggle" @if(isset($designcard->layout['fields']['father'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <div class="field"><label>Text</label><input type="text" id="fatherText" value="Father: Rakesh Sharma"></div>
                            <div class="row4">
                            <div class="field"><label>X</label><input type="number" id="fatherX" value="@php echo $designcard->layout['fields']['father']['x'] ?? 30; @endphp"></div>
                            <div class="field"><label>Y</label><input type="number" id="fatherY" value="@php echo $designcard->layout['fields']['father']['y'] ?? 78; @endphp"></div>
                            <div class="field"><label>Size</label><input type="number" id="fatherSize" value="@php echo $designcard->layout['fields']['father']['fontSize'] ?? 15; @endphp"></div>
                            </div>
                            <div class="row2">
                            <div class="field"><label>Color</label><input type="color" id="fatherColor" value="@php echo $designcard->layout['fields']['father']['color'] ?? '#1f2430'; @endphp"></div>
                                <div class="field"><label>Weight</label>
                                <select id="fatherWeight">
                                    <option value="700" @php echo (isset($designcard->layout['fields']['father']['fontWeight']) && $designcard->layout['fields']['father']['fontWeight'] == 700) ? 'selected' : ''; @endphp>Bold</option>
                                    <option value="400" @php echo (isset($designcard->layout['fields']['fontWeight']['weight']) && $designcard->layout['fields']['father']['fontWeight'] == 400) ? 'selected' : ''; @endphp>Normal</option>
                                </select>
                                </div>
                            </div>
                        </div>
                        </div>

                        <!-- MOTHER'S NAME -->
                        <div class="group">
                        <div class="group-title"><h3>Mother's Name</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="motherToggle" @if(isset($designcard->layout['fields']['mother'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <div class="field"><label>Text</label><input type="text" id="motherText" value="Mother: Anita Sharma"></div>
                            <div class="row4">
                            <div class="field"><label>X</label><input type="number" id="motherX" value="@php echo $designcard->layout['fields']['mother']['x'] ?? 30; @endphp"></div>
                            <div class="field"><label>Y</label><input type="number" id="motherY" value="@php echo $designcard->layout['fields']['mother']['y'] ?? 83; @endphp"></div>
                            <div class="field"><label>Size</label><input type="number" id="motherSize" value="@php echo $designcard->layout['fields']['mother']['fontSize'] ?? 15; @endphp"></div>
                            </div>
                            <div class="row2">
                            <div class="field"><label>Color</label><input type="color" id="motherColor" value="@php echo $designcard->layout['fields']['mother']['color'] ?? '#1f2430'; @endphp"></div>
                                <div class="field"><label>Weight</label>
                                <select id="motherWeight">
                                    <option value="700" @php echo (isset($designcard->layout['fields']['mother']['fontWeight']) && $designcard->layout['fields']['mother']['fontWeight'] == 700) ? 'selected' : ''; @endphp>Bold</option>
                                    <option value="400" @php echo (isset($designcard->layout['fields']['mother']['fontWeight']) && $designcard->layout['fields']['mother']['fontWeight'] == 400) ? 'selected' : ''; @endphp>Normal</option>
                                </select>
                                </div>
                            </div>
                        </div>
                        </div>

                        <!-- CLASS -->
                        <div class="group">
                        <div class="group-title"><h3>Class &amp; Section</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="classToggle" @if(isset($designcard->layout['fields']['class'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <div class="field"><label>Text</label><input type="text" id="classText" value="Class: V - B"></div>
                            <div class="row4">
                            <div class="field"><label>X</label><input type="number" id="classX" value="@php echo $designcard->layout['fields']['class']['x'] ?? 30; @endphp"></div>
                            <div class="field"><label>Y</label><input type="number" id="classY" value="@php echo $designcard->layout['fields']['class']['y'] ?? 48; @endphp"></div>
                            <div class="field"><label>Size</label><input type="number" id="classSize" value="@php echo $designcard->layout['fields']['class']['fontSize'] ?? 15; @endphp"></div>
                            </div>
                            <div class="row2">
                            <div class="field"><label>Color</label><input type="color" id="classColor" value="@php echo $designcard->layout['fields']['class']['color'] ?? '#1f2430'; @endphp"></div>
                            <div class="field"><label>Weight</label>
                                <select id="classWeight">
                                <option value="700" @php echo (isset($designcard->layout['fields']['class']['fontWeight']) && $designcard->layout['fields']['class']['fontWeight'] == 700) ? 'selected' : ''; @endphp>Bold</option>
                                <option value="400" @php echo (isset($designcard->layout['fields']['class']['fontWeight']) && $designcard->layout['fields']['class']['fontWeight'] == 400) ? 'selected' : ''; @endphp>Normal</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- DOB -->
                        <div class="group">
                        <div class="group-title"><h3>Date of Birth</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="dobToggle" @if(isset($designcard->layout['fields']['dob'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <div class="field"><label>Text</label><input type="text" id="dobText" value="DOB: 12-05-2015"></div>
                            <div class="row4">
                            <div class="field"><label>X</label><input type="number" id="dobX" value="@php echo $designcard->layout['fields']['dob']['x'] ?? 30; @endphp"></div>
                            <div class="field"><label>Y</label><input type="number" id="dobY" value="@php echo $designcard->layout['fields']['dob']['y'] ?? 53; @endphp"></div>
                            <div class="field"><label>Size</label><input type="number" id="dobSize" value="@php echo $designcard->layout['fields']['dob']['fontSize'] ?? 15; @endphp"></div>
                            </div>
                            <div class="row2">
                            <div class="field"><label>Color</label><input type="color" id="dobColor" value="@php echo $designcard->layout['fields']['dob']['color'] ?? '#1f2430'; @endphp"></div>
                            <div class="field"><label>Weight</label>
                                <select id="dobWeight">
                                <option value="700" @php echo (isset($designcard->layout['fields']['dob']['fontWeight']) && $designcard->layout['fields']['dob']['fontWeight'] == 700) ? 'selected' : ''; @endphp>Bold</option>
                                <option value="400" @php echo (isset($designcard->layout['fields']['dob']['fontWeight']) && $designcard->layout['fields']['dob']['fontWeight'] == 400) ? 'selected' : ''; @endphp>Normal</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- ADMISSION / ROLL NO -->
                        <div class="group">
                        <div class="group-title"><h3>ID CARD NO</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="admToggle" @if(isset($designcard->layout['fields']['adm'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <div class="field"><label>Text</label><input type="text" id="admText" value="Adm No: MP-2026-0143"></div>
                            <div class="row4">
                            <div class="field"><label>X</label><input type="number" id="admX" value="@php echo $designcard->layout['fields']['adm']['x'] ?? 30; @endphp"></div>
                            <div class="field"><label>Y</label><input type="number" id="admY" value="@php echo $designcard->layout['fields']['adm']['y'] ?? 78; @endphp"></div>
                            <div class="field"><label>Size</label><input type="number" id="admSize" value="@php echo $designcard->layout['fields']['adm']['fontSize'] ?? 15; @endphp"></div>
                            </div>
                            <div class="row2">
                            <div class="field"><label>Color</label><input type="color" id="admColor" value="@php echo $designcard->layout['fields']['adm']['color'] ?? '#1f2430'; @endphp"></div>
                            <div class="field"><label>Weight</label>
                                <select id="admWeight">
                                <option value="700" @php echo (isset($designcard->layout['fields']['adm']['fontWeight']) && $designcard->layout['fields']['adm']['fontWeight'] == 700) ? 'selected' : ''; @endphp>Bold</option>
                                <option value="400" @php echo (isset($designcard->layout['fields']['adm']['fontWeight']) && $designcard->layout['fields']['adm']['fontWeight'] == 400) ? 'selected' : ''; @endphp>Normal</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- BLOOD GROUP / CONTACT -->
                        <div class="group">
                        <div class="group-title"><h3>Contact</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="bloodToggle" @if(isset($designcard->layout['fields']['blood'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <div class="field"><label>Text</label><input type="text" id="bloodText" value=" Ph: 98765 43210"></div>
                            <div class="row4">
                            <div class="field"><label>X</label><input type="number" id="bloodX" value="@php echo $designcard->layout['fields']['blood']['x'] ?? 55; @endphp"></div>
                            <div class="field"><label>Y</label><input type="number" id="bloodY" value="@php echo $designcard->layout['fields']['blood']['y'] ?? 89; @endphp"></div>
                            <div class="field"><label>Size</label><input type="number" id="bloodSize" value="@php echo $designcard->layout['fields']['blood']['fontSize'] ?? 13; @endphp"></div>
                            </div>
                            <div class="row2">
                            <div class="field"><label>Color</label><input type="color" id="bloodColor" value="@php echo $designcard->layout['fields']['blood']['color'] ?? '#ffffff'; @endphp"></div>
                            <div class="field"><label>Weight</label>
                                <select id="bloodWeight">
                                <option value="700" @php echo (isset($designcard->layout['fields']['blood']['fontWeight']) && $designcard->layout['fields']['blood']['fontWeight'] == 700) ? 'selected' : ''; @endphp>Bold</option>
                                <option value="400" @php echo (isset($designcard->layout['fields']['blood']['fontWeight']) && $designcard->layout['fields']['blood']['fontWeight'] == 400) ? 'selected' : ''; @endphp>Normal</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- PRINCIPAL SIGNATURE -->
                        <div class="group">
                        <div class="group-title"><h3>Authority Signature</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="signToggle" @if(isset($designcard->layout['fields']['sign'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <label class="filebtn">Click to upload signature
                            <input type="file" id="signUpload" accept="image/*">
                            </label>
                            <div class="row4">
                            <div class="field"><label>X</label><input type="number" id="signX" value="@php echo $designcard->layout['fields']['sign']['x'] ?? 110; @endphp"></div>
                            <div class="field"><label>Y</label><input type="number" id="signY" value="@php echo $designcard->layout['fields']['sign']['y'] ?? 90; @endphp"></div>
                            <div class="field"><label>W</label><input type="number" id="signW" value="@php echo $designcard->layout['fields']['sign']['width'] ?? 30; @endphp"></div>
                            <div class="field"><label>H</label><input type="number" id="signH" value="@php echo $designcard->layout['fields']['sign']['height'] ?? 30; @endphp"></div>
                            </div>
                            <div style="font-size:11px;color:var(--muted);">Tip: use a signature saved with a transparent background for best results.</div>
                            <div class="row2">
                            <div class="field"><label>Color</label><input type="color" id="signColor" value="#ffffff"></div>
                            <div class="field"><label>Weight</label>
                                <select id="signWeight">
                                <option value="700" selected>Bold</option>
                                <option value="400">Normal</option>
                                </select>
                            </div>
                            </div>
                        </div>
                        </div>

                        <!-- QR CODE -->
                        <div class="group">
                        <div class="group-title"><h3>QR Code</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="qrToggle" @if(isset($designcard->layout['fields']['qr'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <div class="field"><label>Data (usually admission no.)</label><input type="text" id="qrData" value="MP-2026-0143"></div>
                            <div class="row4">
                            <div class="field"><label>X</label><input type="number" id="qrX" value="@php echo $designcard->layout['fields']['qr']['x'] ?? 60; @endphp"></div>
                            <div class="field"><label>Y</label><input type="number" id="qrY" value="@php echo $designcard->layout['fields']['qr']['y'] ?? 80; @endphp"></div>
                            <div class="field"><label>Size</label><input type="number" id="qrSize" value="@php echo $designcard->layout['fields']['qr']['width'] ?? 40; @endphp"></div>
                            </div>
                        </div>
                        </div>

              <div class="group">
                <button class="reset-link" id="resetBtn">↺ Reset all fields to default position</button>
              </div>

            </div>

            <!-- ===================== PREVIEW ===================== -->
            <div class="preview-wrap">

              <div class="zoom-controls">
                Zoom
                <input type="range" id="zoom" min="50" max="150" value="100">
                <span id="zoomVal">100%</span>
              </div>

              <div class="card-stage">
                <div id="idCard" class="id-card">

                  <img id="elLogo" class="el el-logo" src="https://placehold.co/160x160/ffffff/9e1b32?text=Logo" alt="School Logo">
                  <div id="elSchoolName" class="el el-text">Mother's Pride School</div>
                  <div id="elAddress" class="el el-text">123 Education Lane, Varanasi, UP - 221001</div>
                  <div id="elSession" class="el el-text">Session: 2026-2027</div>
                  <img id="elPhoto" class="el el-photo" src="https://placehold.co/300x300/eeeeee/999999?text=Photo" alt="Student Photo">
                  <div id="elStudentAddress" class="el el-text">Address: 24, Green Park, Varanasi, UP - 221001</div>
                  <div id="elTableData" class="el el-tabledata"></div>
                  <div id="elName" class="el el-text">AARAV SHARMA</div>
                  <div id="elFather" class="el el-text">Father: Rakesh Sharma</div>
                  <div id="elMother" class="el el-text">Mother: Anita Sharma</div>
                  <div id="elClass" class="el el-text">Class: V - B</div>
                  <div id="elDob" class="el el-text">DOB: 12-05-2015</div>
                  <div id="elAdm" class="el el-text">Adm No: MP-2026-0143</div>
                  <div id="elBlood" class="el el-text">Blood Group: O+  |  Ph: 98765 43210</div>
                  <img id="elSign" class="el el-sign" src="https://placehold.co/360x120/ffffff/1f2430?text=Signature" alt="Principal Signature">
                  <img id="elQr" class="el el-qr" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=MP-2026-0143" alt="QR">

                </div>
              </div>

              <div class="floating-toolbar">
                <button type="button" id="saveLayoutBtnFloat" class="btn btn-primary mx-1">💾 Save ID Card</button>
               
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
(function () {

    // =========================================================
    // BASIC CARD VARIABLES
    // =========================================================

    const card = document.getElementById('idCard');

    // Card size now comes straight from user input (no horizontal/
    // vertical preset). Change the default values below if you want
    // a different starting size.
    // let CARD_W = {{$width}};
    // let CARD_H = {{$height}};

    // const cardWidthInput  = document.getElementById('cardWidthInput');
    // const cardHeightInput = document.getElementById('cardHeightInput');
    // const applyCardSizeBtn = document.getElementById('applyCardSizeBtn');

    // function applyCardSize() {

    //     const w = Math.max(50, parseInt(cardWidthInput?.value, 10) || CARD_W);
    //     const h = Math.max(50, parseInt(cardHeightInput?.value, 10) || CARD_H);

    //     CARD_W = w;
    //     CARD_H = h;

    //     card.style.width  = CARD_W + 'px';
    //     card.style.height = CARD_H + 'px';
    // }

    // if (cardWidthInput)  cardWidthInput.value  = CARD_W;
    // if (cardHeightInput) cardHeightInput.value = CARD_H;

    // applyCardSize(); // set initial size on load

    // Laravel values are in MM this is changed
    let CARD_W_MM = {{ $width }};
    let CARD_H_MM = {{ $height }};

    // Pixel values used by the editor
    let CARD_W = 0;
    let CARD_H = 0;

    const MM_TO_PX = 96 / 25.4;

    const cardWidthInput  = document.getElementById('cardWidthInput');
    const cardHeightInput = document.getElementById('cardHeightInput');
    const applyCardSizeBtn = document.getElementById('applyCardSizeBtn');


    // MM → PX
    function mmToPx(mm) {
        return mm * MM_TO_PX;
    }


    // PX → MM
    function pxToMm(px) {
        return px / MM_TO_PX;
    }


    function applyCardSize() {

        // Get MM values from inputs
        const wMm = Math.max(
            10,
            parseFloat(cardWidthInput?.value) || CARD_W_MM
        );

        const hMm = Math.max(
            10,
            parseFloat(cardHeightInput?.value) || CARD_H_MM
        );


        // Keep original values in MM
        CARD_W_MM = wMm;
        CARD_H_MM = hMm;


        // Convert MM → PX
        CARD_W = mmToPx(CARD_W_MM);
        CARD_H = mmToPx(CARD_H_MM);


        // Apply PX to editor card
        card.style.width = CARD_W + 'px';
        card.style.height = CARD_H + 'px';
    }


    // Show MM in input boxes
    if (cardWidthInput) {
        cardWidthInput.value = CARD_W_MM;
    }

    if (cardHeightInput) {
        cardHeightInput.value = CARD_H_MM;
    }


    // Apply initial size
    applyCardSize();

    if (applyCardSizeBtn) {
        //applyCardSizeBtn.addEventListener('click', applyCardSize);
       applyCardSizeBtn.addEventListener('click', function () {
            const width = cardWidthInput.value;
            const height = cardHeightInput.value;
            $.ajax({
                url: "{{ route('event-id-cards.size', $idCardData->id) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    width: width,
                    height: height
                },

                success: function (response) {
                     toastr.success('Card size updated successfully!');
                },

                error: function (xhr) {
                    console.log('Error saving size');
                }
            });

        });
    }

    [cardWidthInput, cardHeightInput].forEach(function (input) {
        if (!input) return;
        input.addEventListener('input', applyCardSize);
    });

    // No server-provided layout in this static version — start empty.
    const initialLayout = {};

    const sampleId = @json($idCardData->id ?? null);
    const eventId = @json($idCardData->event_id ?? null);
    const sampleBackground = @json($idCardData->file_path ?? null);

    const tableDataTextarea = document.getElementById('tabledataTextarea');
    const tableDataPreview = document.getElementById('elTableData');
    const tableLeftInput = document.getElementById('tableLeft');
    const tableTopInput = document.getElementById('tableTop');
    const tableWidthInput = document.getElementById('tableWidth');
    const tableHeightInput = document.getElementById('tableHeight');

    function renderTableDataPreview() {
        if (!tableDataPreview || !tableDataTextarea) {
            return;
        }

        const rawValue = tableDataTextarea.value || '';
        const left = parseFloat(tableLeftInput?.value || 30) || 30;
        const top = parseFloat(tableTopInput?.value || 120) || 120;
        const width = parseFloat(tableWidthInput?.value || Math.max(120, CARD_W - 60)) || Math.max(120, CARD_W - 60);
        const height = parseFloat(tableHeightInput?.value || 0) || 0;

        tableDataPreview.innerHTML = rawValue;
        tableDataPreview.style.display = rawValue.trim() ? '' : 'none';
        tableDataPreview.style.left = left + 'px';
        tableDataPreview.style.top = top + 'px';
        tableDataPreview.style.width = width + 'px';
        tableDataPreview.style.height = height > 0 ? height + 'px' : 'auto';
        tableDataPreview.style.minWidth = width + 'px';
        tableDataPreview.style.minHeight = height > 0 ? height + 'px' : '0px';
        tableDataPreview.style.maxWidth = 'none';
        tableDataPreview.style.maxHeight = 'none';
        tableDataPreview.style.overflowY = 'auto';
        tableDataPreview.style.overflowX = 'auto';

        const renderedTable = tableDataPreview.querySelector('table');
        if (renderedTable) {
            renderedTable.style.width = '100%';
            renderedTable.style.height = height > 0 ? '100%' : 'auto';
            renderedTable.style.minHeight = height > 0 ? height + 'px' : '0px';
        }
    }

    [tableDataTextarea, tableLeftInput, tableTopInput, tableWidthInput, tableHeightInput].forEach(function (input) {
        if (!input) {
            return;
        }
        input.addEventListener('input', renderTableDataPreview);
    });

    renderTableDataPreview();


    // =========================================================
    // TABLE VISUAL BUILDER
    // =========================================================
    (function () {

        const gridEditor = document.getElementById('tableGridEditor');
        const buildRowsInput = document.getElementById('tableBuildRows');
        const buildColsInput = document.getElementById('tableBuildCols');
        const newTableBtn = document.getElementById('tableNewBtn');
        const loadHtmlBtn = document.getElementById('tableLoadBtn');
        const addRowBtn = document.getElementById('tableAddRowBtn');
        const delRowBtn = document.getElementById('tableDelRowBtn');
        const addColBtn = document.getElementById('tableAddColBtn');
        const delColBtn = document.getElementById('tableDelColBtn');
        const borderWidthInput = document.getElementById('tableBorderWidth');
        const borderColorInput = document.getElementById('tableBorderColor');
        const fontSizeInput = document.getElementById('tableFontSize');
        const textAlignInput = document.getElementById('tableTextAlign');
        const headerToggleInput = document.getElementById('tableHeaderToggle');

        const paddingTopInput = document.getElementById('tablePaddingTop');
        const paddingRightInput = document.getElementById('tablePaddingRight');
        const paddingBottomInput = document.getElementById('tablePaddingBottom');
        const paddingLeftInput = document.getElementById('tablePaddingLeft');
        const marginTopInput = document.getElementById('tableMarginTop');
        const marginRightInput = document.getElementById('tableMarginRight');
        const marginBottomInput = document.getElementById('tableMarginBottom');
        const marginLeftInput = document.getElementById('tableMarginLeft');

        if (!gridEditor || !tableDataTextarea) {
            return;
        }

        let activeElement = null;

        function highlightActiveElement() {
            Array.from(gridEditor.children).forEach(function (child) {
                child.style.outline = (child === activeElement)
                    ? '2px dashed var(--accent, #2f6fed)'
                    : 'none';
                child.style.outlineOffset = '2px';
            });
        }

        function selectElement(el) {
            if (!el || el.parentElement !== gridEditor) {
                return;
            }
            activeElement = el;
            highlightActiveElement();
        }

        gridEditor.addEventListener('click', function (e) {
            let node = e.target;
            while (node && node.parentElement !== gridEditor) {
                node = node.parentElement;
            }
            if (node) {
                selectElement(node);
            }
        });

        function currentElement() {
            if (activeElement && activeElement.parentElement === gridEditor) {
                return activeElement;
            }
            return gridEditor.lastElementChild;
        }

        function currentTable() {
            const el = currentElement();
            return el && el.tagName === 'TABLE' ? el : null;
        }

        function styleSettings() {
            return {
                borderWidth: parseFloat(borderWidthInput?.value || 1) || 0,
                borderColor: borderColorInput?.value || '#1f2430',
                paddingTop: parseFloat(paddingTopInput?.value ?? 3) || 0,
                paddingRight: parseFloat(paddingRightInput?.value ?? 3) || 0,
                paddingBottom: parseFloat(paddingBottomInput?.value ?? 3) || 0,
                paddingLeft: parseFloat(paddingLeftInput?.value ?? 3) || 0,
                marginTop: parseFloat(marginTopInput?.value ?? 0) || 0,
                marginRight: parseFloat(marginRightInput?.value ?? 0) || 0,
                marginBottom: parseFloat(marginBottomInput?.value ?? 0) || 0,
                marginLeft: parseFloat(marginLeftInput?.value ?? 0) || 0,
                fontSize: parseFloat(fontSizeInput?.value || 12) || 12,
                align: textAlignInput?.value || 'left',
                header: !!headerToggleInput?.checked
            };
        }

        function applyPaddingMargin(target, s) {
            target.style.paddingTop = s.paddingTop + 'px';
            target.style.paddingRight = s.paddingRight + 'px';
            target.style.paddingBottom = s.paddingBottom + 'px';
            target.style.paddingLeft = s.paddingLeft + 'px';
        }

        function applyStylesToElement(el) {
            if (!el) {
                return;
            }

            const s = styleSettings();

            if (el.tagName === 'TABLE') {
                el.style.width = '100%';
                el.style.borderCollapse = 'collapse';
                el.style.fontSize = s.fontSize + 'px';
                el.style.marginTop = s.marginTop + 'px';
                el.style.marginRight = s.marginRight + 'px';
                el.style.marginBottom = s.marginBottom + 'px';
                el.style.marginLeft = s.marginLeft + 'px';

                Array.from(el.rows).forEach(function (row, rIdx) {
                    Array.from(row.cells).forEach(function (cell) {
                        cell.style.border = s.borderWidth + 'px solid ' + s.borderColor;
                        applyPaddingMargin(cell, s);
                        cell.style.textAlign = s.align;
                        cell.style.verticalAlign = 'top';

                        if (rIdx === 0 && s.header) {
                            cell.style.fontWeight = '700';
                            cell.style.background = 'rgba(158,27,50,0.08)';
                        } else {
                            cell.style.fontWeight = '400';
                            cell.style.background = 'transparent';
                        }
                    });
                });

                return;
            }

            el.style.border = s.borderWidth + 'px solid ' + s.borderColor;
            el.style.fontSize = s.fontSize + 'px';
            el.style.textAlign = s.align;
            el.style.boxSizing = 'border-box';
            applyPaddingMargin(el, s);
            el.style.marginTop = s.marginTop + 'px';
            el.style.marginRight = s.marginRight + 'px';
            el.style.marginBottom = s.marginBottom + 'px';
            el.style.marginLeft = s.marginLeft + 'px';

            if (s.header) {
                el.style.fontWeight = '700';
                el.style.background = 'rgba(158,27,50,0.08)';
            } else {
                el.style.fontWeight = '400';
            }
        }

        const applyStylesToTable = applyStylesToElement;

        function attachCellEditing(el) {
            if (!el) {
                return;
            }

            if (el.tagName === 'TABLE') {
                Array.from(el.querySelectorAll('td, th')).forEach(function (cell) {
                    cell.setAttribute('contenteditable', 'true');
                    cell.addEventListener('input', syncTextareaFromGrid);
                    cell.addEventListener('blur', syncTextareaFromGrid);
                });
                return;
            }

            el.setAttribute('contenteditable', 'true');
            el.addEventListener('input', syncTextareaFromGrid);
            el.addEventListener('blur', syncTextareaFromGrid);
        }

        function syncTextareaFromGrid() {
            const blocks = Array.from(gridEditor.children);

            if (!blocks.length) {
                return;
            }

            const html = blocks.map(function (el) {
                const clone = el.cloneNode(true);

                clone.removeAttribute('contenteditable');
                clone.style.outline = '';
                clone.style.outlineOffset = '';

                Array.from(clone.querySelectorAll('[contenteditable]')).forEach(function (node) {
                    node.removeAttribute('contenteditable');
                });

                return clone.outerHTML;
            }).join('\n\n');

            tableDataTextarea.value = html;
            tableDataTextarea.dispatchEvent(new Event('input', { bubbles: true }));
        }

        function buildNewTable(rows, cols) {
            rows = Math.max(1, Math.min(20, rows || 1));
            cols = Math.max(1, Math.min(10, cols || 1));

            const table = document.createElement('table');

            for (let r = 0; r < rows; r++) {
                const tr = table.insertRow();
                for (let c = 0; c < cols; c++) {
                    const cell = tr.insertCell();
                    cell.textContent = r === 0 ? ('Header ' + (c + 1)) : '';
                }
            }

            gridEditor.innerHTML = '';
            gridEditor.appendChild(table);
            selectElement(table);
            applyStylesToElement(table);
            attachCellEditing(table);
            syncTextareaFromGrid();
        }

        function buildStudentInfoTable() {
            const rowsData = [
                ['Name', 'Rahul Kumar'],
                ['Father', 'Rajesh Kumar'],
                ['Class', '8-A'],
                ['DOB', '10/05/2012'],
                ['Adm. No.', 'ADM001'],
                ['Blood', 'O+']
            ];

            const table = document.createElement('table');

            rowsData.forEach(function (pair) {
                const tr = table.insertRow();
                const labelCell = tr.insertCell();
                const valueCell = tr.insertCell();

                labelCell.textContent = pair[0];
                valueCell.textContent = pair[1];

                labelCell.style.width = '34%';
                labelCell.style.fontWeight = '700';
                labelCell.style.color = '#4b5563';
                labelCell.style.background = '#f5f6f8';
            });

            gridEditor.innerHTML = '';
            gridEditor.appendChild(table);
            selectElement(table);

            if (borderWidthInput) borderWidthInput.value = 1;
            if (borderColorInput) borderColorInput.value = '#cbd0d6';
            if (paddingTopInput) paddingTopInput.value = 4;
            if (paddingRightInput) paddingRightInput.value = 4;
            if (paddingBottomInput) paddingBottomInput.value = 4;
            if (paddingLeftInput) paddingLeftInput.value = 4;
            if (fontSizeInput) fontSizeInput.value = 11;
            if (textAlignInput) textAlignInput.value = 'left';
            if (headerToggleInput) headerToggleInput.checked = false;

            applyStylesToElement(table);
            attachCellEditing(table);
            syncTextareaFromGrid();

            if (tableHeightInput) {
                tableHeightInput.value = 0;
                tableHeightInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }

        function loadFromTextarea() {
            const raw = tableDataTextarea.value || '';
            const temp = document.createElement('div');

            temp.innerHTML = raw;

            const blocks = Array.from(temp.children);

            if (!blocks.length) {
                alert('No HTML element found in the box. Type or paste a <table> and/or <div> (or click "New Table") and try again.');
                return;
            }

            gridEditor.innerHTML = '';

            let firstTable = null;

            blocks.forEach(function (el) {
                gridEditor.appendChild(el);
                attachCellEditing(el);

                if (!firstTable && el.tagName === 'TABLE') {
                    firstTable = el;
                }
            });

            selectElement(firstTable || gridEditor.lastElementChild);
            syncTextareaFromGrid();
        }

        if (newTableBtn) {
            newTableBtn.addEventListener('click', function () {
                buildNewTable(
                    parseInt(buildRowsInput?.value, 10) || 3,
                    parseInt(buildColsInput?.value, 10) || 2
                );
            });
        }

        if (loadHtmlBtn) {
            loadHtmlBtn.addEventListener('click', loadFromTextarea);
        }

        const presetStudentBtn = document.getElementById('tablePresetStudentBtn');

        if (presetStudentBtn) {
            presetStudentBtn.addEventListener('click', buildStudentInfoTable);
        }

        if (addRowBtn) {
            addRowBtn.addEventListener('click', function () {
                const table = currentTable();
                if (!table) return;

                const cols = table.rows.length ? table.rows[0].cells.length : 1;
                const tr = table.insertRow();

                for (let c = 0; c < cols; c++) {
                    tr.insertCell().textContent = '';
                }

                applyStylesToTable(table);
                attachCellEditing(table);
                syncTextareaFromGrid();
            });
        }

        if (delRowBtn) {
            delRowBtn.addEventListener('click', function () {
                const table = currentTable();
                if (!table || table.rows.length <= 1) return;

                table.deleteRow(table.rows.length - 1);
                syncTextareaFromGrid();
            });
        }

        if (addColBtn) {
            addColBtn.addEventListener('click', function () {
                const table = currentTable();
                if (!table) return;

                Array.from(table.rows).forEach(function (row) {
                    row.insertCell().textContent = '';
                });

                applyStylesToTable(table);
                attachCellEditing(table);
                syncTextareaFromGrid();
            });
        }

        if (delColBtn) {
            delColBtn.addEventListener('click', function () {
                const table = currentTable();
                if (!table || !table.rows.length || table.rows[0].cells.length <= 1) return;

                Array.from(table.rows).forEach(function (row) {
                    row.deleteCell(row.cells.length - 1);
                });

                syncTextareaFromGrid();
            });
        }

        [
            borderWidthInput, borderColorInput,
            paddingTopInput, paddingRightInput, paddingBottomInput, paddingLeftInput,
            marginTopInput, marginRightInput, marginBottomInput, marginLeftInput,
            fontSizeInput, textAlignInput, headerToggleInput
        ].forEach(function (input) {
            if (!input) return;

            input.addEventListener('input', function () {
                const el = currentElement();
                if (!el) return;
                applyStylesToElement(el);
                syncTextareaFromGrid();
            });

            input.addEventListener('change', function () {
                const el = currentElement();
                if (!el) return;
                applyStylesToElement(el);
                syncTextareaFromGrid();
            });
        });

        if ((tableDataTextarea.value || '').trim()) {
            loadFromTextarea();
        }

    })();


    // =========================================================
    // BACKGROUND AREAS / SHAPES
    // =========================================================

    const backgroundAreasContainer = document.getElementById('backgroundAreasContainer');
    const addBackgroundAreaBtn = document.getElementById('addBackgroundAreaBtn');

    let backgroundAreas = [];

    function generateBackgroundAreaId() {
        return 'backgroundArea_' + Date.now() + '_' + Math.floor(Math.random() * 100000);
    }

    function createBackgroundAreaElement(area) {
        const el = document.createElement('div');

        el.id = area.id;
        el.className = 'background-area';
        el.dataset.backgroundArea = 'true';
        el.style.left = (parseFloat(area.x) || 0) + 'px';
        el.style.top = (parseFloat(area.y) || 0) + 'px';
        el.style.width = (parseFloat(area.width) || 100) + 'px';
        el.style.height = (parseFloat(area.height) || 50) + 'px';
        el.style.backgroundColor = area.backgroundColor || '#9e1b32';
        el.style.opacity = area.opacity !== undefined ? area.opacity : 1;
        el.style.borderRadius = (parseFloat(area.borderRadius) || 0) + 'px';

        if (area.visible === false) {
            el.style.display = 'none';
        }

        const firstField = card.querySelector('.el');

        if (firstField) {
            card.insertBefore(el, firstField);
        } else {
            card.appendChild(el);
        }

        return el;
    }

    function createBackgroundAreaControl(area) {

        if (!backgroundAreasContainer) {
            return;
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'background-area-control';
        wrapper.dataset.areaId = area.id;
        wrapper.style.cssText = 'border:1px solid #e1e3e7;border-radius:7px;padding:10px;background:#fafbfc;margin-bottom:8px;';

        const header = document.createElement('div');
        header.style.cssText = 'display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;';

        const title = document.createElement('strong');
        title.textContent = area.name || 'Background Area';
        title.style.cssText = 'font-size:12px;color:var(--maroon-dark);';

        const visibilityLabel = document.createElement('label');
        visibilityLabel.className = 'switch';
        visibilityLabel.innerHTML = '<input type="checkbox" class="background-area-visible" ' +
            (area.visible !== false ? 'checked' : '') + '><span class="slider"></span>';

        header.appendChild(title);
        header.appendChild(visibilityLabel);
        wrapper.appendChild(header);

        const positionRow = document.createElement('div');
        positionRow.className = 'row4';
        positionRow.innerHTML =
            '<div class="field"><label>X</label><input type="number" class="background-area-x" value="' + (parseFloat(area.x) || 0) + '"></div>' +
            '<div class="field"><label>Y</label><input type="number" class="background-area-y" value="' + (parseFloat(area.y) || 0) + '"></div>' +
            '<div class="field"><label>W</label><input type="number" min="1" class="background-area-width" value="' + (parseFloat(area.width) || 100) + '"></div>' +
            '<div class="field"><label>H</label><input type="number" min="1" class="background-area-height" value="' + (parseFloat(area.height) || 50) + '"></div>';
        wrapper.appendChild(positionRow);

        const styleRow = document.createElement('div');
        styleRow.className = 'row2';
        styleRow.innerHTML =
            '<div class="field"><label>Color</label><input type="color" class="background-area-color" value="' + (area.backgroundColor || '#9e1b32') + '"></div>' +
            '<div class="field"><label>Opacity</label><input type="number" class="background-area-opacity" min="0" max="1" step="0.05" value="' + (area.opacity !== undefined ? area.opacity : 1) + '"></div>';
        wrapper.appendChild(styleRow);

        const radiusRow = document.createElement('div');
        radiusRow.className = 'row2';
        radiusRow.innerHTML =
            '<div class="field"><label>Radius</label><input type="number" class="background-area-radius" min="0" value="' + (parseFloat(area.borderRadius) || 0) + '"></div>' +
            '<div style="display:flex;align-items:flex-end;justify-content:flex-end;"><button type="button" class="background-area-delete" style="background:#dc3545;color:#fff;border:none;border-radius:5px;padding:7px 10px;cursor:pointer;font-size:11px;">Delete</button></div>';
        wrapper.appendChild(radiusRow);

        backgroundAreasContainer.appendChild(wrapper);

        const el = document.getElementById(area.id);

        if (!el) {
            return;
        }

        const xInput = wrapper.querySelector('.background-area-x');
        const yInput = wrapper.querySelector('.background-area-y');
        const widthInput = wrapper.querySelector('.background-area-width');
        const heightInput = wrapper.querySelector('.background-area-height');
        const colorInput = wrapper.querySelector('.background-area-color');
        const opacityInput = wrapper.querySelector('.background-area-opacity');
        const radiusInput = wrapper.querySelector('.background-area-radius');
        const visibleInput = wrapper.querySelector('.background-area-visible');

        function updateArea() {

            const x = parseFloat(xInput.value) || 0;
            const y = parseFloat(yInput.value) || 0;
            const width = Math.max(1, parseFloat(widthInput.value) || 1);
            const height = Math.max(1, parseFloat(heightInput.value) || 1);
            const color = colorInput.value || '#9e1b32';
            const opacityValue = parseFloat(opacityInput.value);
            const opacity = Number.isFinite(opacityValue) ? Math.max(0, Math.min(1, opacityValue)) : 1;
            const radius = Math.max(0, parseFloat(radiusInput.value) || 0);

            el.style.left = x + 'px';
            el.style.top = y + 'px';
            el.style.width = width + 'px';
            el.style.height = height + 'px';
            el.style.backgroundColor = color;
            el.style.opacity = opacity;
            el.style.borderRadius = radius + 'px';
            el.style.display = visibleInput.checked ? '' : 'none';

            area.x = x;
            area.y = y;
            area.width = width;
            area.height = height;
            area.backgroundColor = color;
            area.opacity = opacity;
            area.borderRadius = radius;
            area.visible = visibleInput.checked;
        }

        [xInput, yInput, widthInput, heightInput, colorInput, opacityInput, radiusInput].forEach(function (input) {
            input.addEventListener('input', updateArea);
        });

        visibleInput.addEventListener('change', updateArea);

        const deleteBtn = wrapper.querySelector('.background-area-delete');

        deleteBtn.addEventListener('click', function (e) {
            e.stopPropagation();

            if (!confirm('Delete this background area?')) {
                return;
            }

            el.remove();
            wrapper.remove();

            backgroundAreas = backgroundAreas.filter(function (item) {
                return item.id !== area.id;
            });

            updateBackgroundAreasEmptyState();
        });

        updateArea();
    }

    function updateBackgroundAreasEmptyState() {
        const empty = document.getElementById('backgroundAreasEmpty');
        if (!empty) return;
        empty.style.display = backgroundAreas.length ? 'none' : 'block';
    }

    function addBackgroundArea() {

        const number = backgroundAreas.length + 1;

        const area = {
            id: generateBackgroundAreaId(),
            name: 'Background Area ' + number,
            x: 20,
            y: 20,
            width: 100,
            height: 50,
            backgroundColor: '#9e1b32',
            opacity: 1,
            borderRadius: 0,
            visible: true
        };

        backgroundAreas.push(area);
        createBackgroundAreaElement(area);
        createBackgroundAreaControl(area);
        updateBackgroundAreasEmptyState();
    }

    if (addBackgroundAreaBtn) {
        addBackgroundAreaBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            addBackgroundArea();
        });
    }

    updateBackgroundAreasEmptyState();


    // =========================================================
    // EXIF ORIENTATION
    // =========================================================

    function readExifOrientation(arrayBuffer) {

        const view = new DataView(arrayBuffer);

        if (view.byteLength < 4 || view.getUint16(0, false) !== 0xFFD8) {
            return 1;
        }

        let offset = 2;

        while (offset < view.byteLength - 1) {

            const marker = view.getUint16(offset, false);
            offset += 2;

            if (marker === 0xFFE1) {

                if (view.getUint32(offset + 2, false) !== 0x45786966) {
                    return 1;
                }

                const tiffOffset = offset + 8;
                const little = view.getUint16(tiffOffset, false) === 0x4949;
                const firstIFDOffset = view.getUint32(tiffOffset + 4, little);
                const dirStart = tiffOffset + firstIFDOffset;
                const entries = view.getUint16(dirStart, little);

                for (let i = 0; i < entries; i++) {

                    const entryOffset = dirStart + 2 + i * 12;

                    if (view.getUint16(entryOffset, little) === 0x0112) {
                        return view.getUint16(entryOffset + 8, little);
                    }
                }

                return 1;

            } else if ((marker & 0xFF00) !== 0xFF00) {
                break;
            } else {
                offset += view.getUint16(offset, false);
            }
        }

        return 1;
    }


    // =========================================================
    // NORMALIZE IMAGE (fixes phone-camera rotation before preview)
    // =========================================================

    function normalizeImage(file, callback) {

        const reader = new FileReader();

        reader.onload = function (e) {

            const arrayBuffer = e.target.result;
            let orientation = 1;

            try {
                orientation = readExifOrientation(arrayBuffer);
            } catch (error) {
                orientation = 1;
            }

            const url = URL.createObjectURL(new Blob([arrayBuffer]));
            const img = new Image();

            img.onload = function () {

                const w = img.naturalWidth;
                const h = img.naturalHeight;

                const canvas = document.createElement('canvas');
                const rotated = orientation >= 5 && orientation <= 8;

                canvas.width = rotated ? h : w;
                canvas.height = rotated ? w : h;

                const ctx = canvas.getContext('2d');

                switch (orientation) {
                    case 2: ctx.transform(-1, 0, 0, 1, w, 0); break;
                    case 3: ctx.transform(-1, 0, 0, -1, w, h); break;
                    case 4: ctx.transform(1, 0, 0, -1, 0, h); break;
                    case 5: ctx.transform(0, 1, 1, 0, 0, 0); break;
                    case 6: ctx.transform(0, 1, -1, 0, h, 0); break;
                    case 7: ctx.transform(0, -1, -1, 0, h, w); break;
                    case 8: ctx.transform(0, -1, 1, 0, 0, w); break;
                }

                ctx.drawImage(img, 0, 0);
                URL.revokeObjectURL(url);

                callback(canvas.toDataURL('image/jpeg', 0.92), canvas.width, canvas.height);
            };

            img.src = url;
        };

        reader.readAsArrayBuffer(file);
    }


    // =========================================================
    // BACKGROUND UPLOAD
    // Previews immediately, then (optionally) uploads to your
    // server. Replace UPLOAD_ENDPOINT with your real route.
    // =========================================================

    
        // =========================================================
    // BACKGROUND UPLOAD
    // No separate upload call — the file is held here and sent
    // together with the rest of the layout when "Save ID Card"
    // is clicked. The controller stores it and puts the resulting
    // path into the `background` column.
    // =========================================================

    const SAVE_ENDPOINT = "{{ route('idcardlayout.store') }}";

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // const bgUpload = document.getElementById('bgUpload');
    // let selectedBackgroundFile = null; // set when the user picks a file, sent on Save

    // if (bgUpload) {

    //     bgUpload.addEventListener('change', function (e) {

    //         const file = e.target.files[0];

    //         if (!file) {
    //             return;
    //         }

    //         selectedBackgroundFile = file;

    //         // Instant local preview only — the actual save happens on
    //         // "Save ID Card".
    //         normalizeImage(file, function (dataUrl) {
    //             card.style.backgroundImage = 'url("' + dataUrl + '")';
    //             card.style.backgroundSize = '100% 100%';
    //         });
    //     });
    // }

    const bgUpload = document.getElementById('bgUpload');

    let selectedBackgroundFile = null;

    if (bgUpload) {

        bgUpload.addEventListener('change', function (e) {

            const file = e.target.files[0];

            if (!file) {
                return;
            }

            selectedBackgroundFile = file;

            /*
            |--------------------------------------------------------------------------
            | 1. Instant local preview
            |--------------------------------------------------------------------------
            */
            normalizeImage(file, function (dataUrl) {

                card.style.backgroundImage = 'url("' + dataUrl + '")';
                card.style.backgroundSize = '100% 100%';
                card.style.backgroundRepeat = 'no-repeat';
                card.style.backgroundPosition = 'center';

            });


            /*
            |--------------------------------------------------------------------------
            | 2. Upload image to server immediately
            |--------------------------------------------------------------------------
            */
            let formData = new FormData();

            // Your database/file field name
            formData.append('file_path', file);

            // CSRF
            formData.append('_token', '{{ csrf_token() }}');

            $('#bgUploadStatus').html(
                '<span style="color:#666;">Uploading...</span>'
            );


            $.ajax({

                url: "{{ route('event-id-cards.background', $idCardData->id) }}",

                type: "POST",

                data: formData,

                processData: false,

                contentType: false,


                /*
                |--------------------------------------------------------------------------
                | Success
                |--------------------------------------------------------------------------
                */
                success: function (response) {

                    if (response.success) {

                        $('#bgUploadStatus').html(
                            '<span style="color:green;">' +
                            response.message +
                            '</span>'
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Update server background URL
                        |--------------------------------------------------------------------------
                        */
                        if (response.background_url) {

                            const backgroundUrl =
                                response.background_url + '?' + Date.now();


                            // Update card preview
                            $('.card-preview').css(
                                'background-image',
                                'url("' + backgroundUrl + '")'
                            );

                            $('.card-preview').css(
                                'background-size',
                                '100% 100%'
                            );

                            $('.card-preview').css(
                                'background-repeat',
                                'no-repeat'
                            );

                            $('.card-preview').css(
                                'background-position',
                                'center'
                            );


                            // Also update `card` if it is a different element
                            if (typeof card !== 'undefined' && card) {

                                card.style.backgroundImage =
                                    'url("' + backgroundUrl + '")';

                                card.style.backgroundSize = '100% 100%';
                                card.style.backgroundRepeat = 'no-repeat';
                                card.style.backgroundPosition = 'center';
                            }
                        }

                    } else {

                        $('#bgUploadStatus').html(
                            '<span style="color:red;">Upload failed.</span>'
                        );
                    }
                },


                /*
                |--------------------------------------------------------------------------
                | Error
                |--------------------------------------------------------------------------
                */
                error: function (xhr) {

                    let message = 'Something went wrong.';

                    if (xhr.responseJSON) {

                        if (xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        if (
                            xhr.responseJSON.errors &&
                            xhr.responseJSON.errors.file_path
                        ) {
                            message =
                                xhr.responseJSON.errors.file_path[0];
                        }
                    }

                    $('#bgUploadStatus').html(
                        '<span style="color:red;">' +
                        message +
                        '</span>'
                    );
                }

            });

        });
    }

    // =========================================================
    // FIELD CONFIGURATION
    // =========================================================

    function getFieldLabel(key) {

        if (!key) {
            return '';
        }

        const label = key
            .replace(/([a-z])([A-Z])/g, '$1 $2')
            .replace(/[_-]+/g, ' ')
            .trim();

        return label.split(' ').map(function (word) {
            if (!word) return '';
            return word.charAt(0).toUpperCase() + word.slice(1);
        }).join(' ');
    }

    const fields = [
        { key: 'logo', el: 'elLogo', x: 'logoX', y: 'logoY', w: 'logoW', h: 'logoH', toggle: 'logoToggle' },
        { key: 'schoolName', el: 'elSchoolName', x: 'schoolNameX', y: 'schoolNameY', text: 'schoolNameText', size: 'schoolNameSize', color: 'schoolNameColor', weight: 'schoolNameWeight', toggle: 'schoolNameToggle' },
        { key: 'address', el: 'elAddress', x: 'addressX', y: 'addressY', text: 'addressText', size: 'addressSize', color: 'addressColor', weight: 'addressWeight', toggle: 'addressToggle' },
        { key: 'studentAddress', el: 'elStudentAddress', x: 'studentAddressX', y: 'studentAddressY', text: 'studentAddressText', size: 'studentAddressSize', color: 'studentAddressColor', weight: 'studentAddressWeight', toggle: 'studentAddressToggle' },
        { key: 'session', el: 'elSession', x: 'sessionX', y: 'sessionY', text: 'sessionText', size: 'sessionSize', color: 'sessionColor', weight: 'sessionWeight', toggle: 'sessionToggle' },
        { key: 'photo', el: 'elPhoto', x: 'photoX', y: 'photoY', w: 'photoW', h: 'photoH', radius: 'photoRadius', toggle: 'photoToggle' },
        { key: 'name', el: 'elName', x: 'nameX', y: 'nameY', text: 'nameText', size: 'nameSize', color: 'nameColor', weight: 'nameWeight', toggle: 'nameToggle' },
        { key: 'father', el: 'elFather', x: 'fatherX', y: 'fatherY', text: 'fatherText', size: 'fatherSize', color: 'fatherColor', weight: 'fatherWeight', toggle: 'fatherToggle' },
        { key: 'mother', el: 'elMother', x: 'motherX', y: 'motherY', text: 'motherText', size: 'motherSize', color: 'motherColor', weight: 'motherWeight', toggle: 'motherToggle' },
        { key: 'class', el: 'elClass', x: 'classX', y: 'classY', text: 'classText', size: 'classSize', color: 'classColor', weight: 'classWeight', toggle: 'classToggle' },
        { key: 'dob', el: 'elDob', x: 'dobX', y: 'dobY', text: 'dobText', size: 'dobSize', color: 'dobColor', weight: 'dobWeight', toggle: 'dobToggle' },
        { key: 'adm', el: 'elAdm', x: 'admX', y: 'admY', text: 'admText', size: 'admSize', color: 'admColor', weight: 'admWeight', toggle: 'admToggle' },
        { key: 'blood', el: 'elBlood', x: 'bloodX', y: 'bloodY', text: 'bloodText', size: 'bloodSize', color: 'bloodColor', weight: 'bloodWeight', toggle: 'bloodToggle' },
        { key: 'sign', el: 'elSign', x: 'signX', y: 'signY', w: 'signW', h: 'signH', color: 'signColor', weight: 'signWeight', toggle: 'signToggle' },
        { key: 'qr', el: 'elQr', x: 'qrX', y: 'qrY', w: 'qrSize', h: 'qrSize', toggle: 'qrToggle' }
    ];


    // =========================================================
    // APPLY FIELD
    // =========================================================

    function applyField(f) {

        const el = document.getElementById(f.el);

        if (!el) {
            return;
        }

        if (f.x) {
            const input = document.getElementById(f.x);
            if (input) el.style.left = (input.value || 0) + 'px';
        }

        if (f.y) {
            const input = document.getElementById(f.y);
            if (input) el.style.top = (input.value || 0) + 'px';
        }

        if (f.w) {
            const input = document.getElementById(f.w);
            if (input) el.style.width = (input.value || 0) + 'px';
        }

        if (f.h) {
            const input = document.getElementById(f.h);
            if (input) el.style.height = (input.value || 0) + 'px';
        }

        if (f.radius) {
            const input = document.getElementById(f.radius);
            if (input) el.style.borderRadius = (input.value || 0) + 'px';
        }

        if (f.text) {
            const input = document.getElementById(f.text);
            if (input) el.textContent = input.value;
        }

        if (f.size) {
            const input = document.getElementById(f.size);
            if (input) el.style.fontSize = (input.value || 12) + 'px';
        }

        if (f.color) {
            const input = document.getElementById(f.color);
            if (input) el.style.color = input.value;
        }

        if (f.weight) {
            const input = document.getElementById(f.weight);
            if (input) el.style.fontWeight = input.value;
        }

        if (f.toggle) {
            const toggle = document.getElementById(f.toggle);

            if (toggle) {
                const visible = toggle.checked;
                el.style.display = visible ? '' : 'none';

                const group = toggle.closest('.group');

                if (group) {
                    group.classList.toggle('field-off', !visible);
                }
            }
        }
    }

    function applyCustomCss(elId, cssText) {
        const styleId = 'css_' + elId;
        let styleEl = document.getElementById(styleId);
        if (!styleEl) {
            styleEl = document.createElement('style');
            styleEl.id = styleId;
            document.head.appendChild(styleEl);
        }
        styleEl.textContent = '#' + elId + ' { ' + (cssText || '') + ' }';
    }


    // =========================================================
    // CONNECT CONTROLS
    // =========================================================

    function wireField(f) {

        ['x', 'y', 'w', 'h', 'radius', 'text', 'size', 'color', 'weight', 'toggle'].forEach(function (key) {

            if (!f[key]) {
                return;
            }

            const input = document.getElementById(f[key]);

            if (!input) {
                return;
            }

            const event = key === 'toggle' ? 'change' : 'input';

            input.addEventListener(event, function () {
                applyField(f);
            });
        });

        applyField(f);
    }

    fields.forEach(function (f) {
        wireField(f);
    });

    document.querySelectorAll('.layout-tab-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            const tab = button.dataset.tab;

            document.querySelectorAll('.layout-tab-btn').forEach(function (item) {
                const isActive = item === button;
                item.classList.toggle('active', isActive);
                item.style.background = isActive ? 'var(--maroon)' : '#f8fafc';
                item.style.color = isActive ? '#fff' : 'var(--muted)';
            });

            document.querySelectorAll('.layout-tab-panel').forEach(function (panel) {
                panel.style.display = panel.id === tab ? 'block' : 'none';
            });

            const inlineTextarea = document.getElementById('usevalTextarea');
            if (inlineTextarea) {
                inlineTextarea.disabled = tab !== 'inline-tab';
            }
        });
    });

    // Small custom-CSS textarea under each field group
    fields.forEach(function (f) {
        const refId = f.x || f.text || f.w || f.h || f.size || f.toggle;
        if (!refId) return;
        const ref = document.getElementById(refId);
        if (!ref) return;
        const group = ref.closest('.group');
        if (!group) return;

        if (group.querySelector('#' + f.key + 'Css')) {
            return;
        }

        const wrapper = document.createElement('div');
        wrapper.className = 'field';

        const label = document.createElement('label');
        label.textContent = 'CSS';

        const ta = document.createElement('textarea');
        ta.id = f.key + 'Css';
        ta.className = 'field-css';
        ta.placeholder = 'Custom CSS for the element (e.g. transform: rotate(3deg);)';
        ta.rows = 2;

        ta.addEventListener('input', function () {
            applyCustomCss(f.el, ta.value);
        });

        wrapper.appendChild(label);
        wrapper.appendChild(ta);

        const body = group.querySelector('.group-body');
        if (body) body.appendChild(wrapper);

        applyCustomCss(f.el, ta.value);
    });


    // =========================================================
    // SHOW ALL / HIDE ALL
    // =========================================================

    const showAllBtn = document.getElementById('showAllBtn');

    if (showAllBtn) {
        showAllBtn.addEventListener('click', function () {
            fields.forEach(function (f) {
                if (!f.toggle) return;
                const toggle = document.getElementById(f.toggle);
                toggle.checked = true;
                applyField(f);
            });
        });
    }

    const hideAllBtn = document.getElementById('hideAllBtn');

    if (hideAllBtn) {
        hideAllBtn.addEventListener('click', function () {
            fields.forEach(function (f) {
                if (!f.toggle) return;
                const toggle = document.getElementById(f.toggle);
                toggle.checked = false;
                applyField(f);
            });
        });
    }


    // =========================================================
    // DEFAULT FONT SETTINGS
    // =========================================================

    const nameElement = document.getElementById('elName');
    if (nameElement) {
        nameElement.style.fontWeight = '700';
        nameElement.style.textTransform = 'uppercase';
    }

    const schoolNameElement = document.getElementById('elSchoolName');
    if (schoolNameElement) {
        schoolNameElement.style.fontWeight = '700';
    }


    // =========================================================
    // QR CODE
    // =========================================================

    const qrDataInput = document.getElementById('qrData');

    function updateQr() {
        if (!qrDataInput) return;

        const value = encodeURIComponent(qrDataInput.value || '');
        const qr = document.getElementById('elQr');

        if (qr) {
            qr.src = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + value;
        }
    }

    if (qrDataInput) {
        qrDataInput.addEventListener('input', updateQr);
        updateQr();
    }


    // =========================================================
    // PHOTO / LOGO / SIGNATURE UPLOAD
    // Uploads the file to the server as soon as it's picked, the
    // same way the background image already does. The server
    // stores the file and hands back a short relative path, which
    // is what gets saved into the layout JSON — never the raw
    // base64 data URL. That keeps `layout` small in the database.
    // =========================================================

    // Raw values exactly as stored on this card right now. If this
    // card was saved BEFORE this fix, these may still be the old,
    // huge base64 strings — kept here only so they can be migrated
    // to a real upload below, never to be written back as-is.
    const legacyStoredImages = {
        photo: @json($designcard->layout['fields']['photo']['src'] ?? null),
        logo:  @json($designcard->layout['fields']['logo']['src'] ?? null),
        sign:  @json($designcard->layout['fields']['sign']['src'] ?? null)
    };

    function isDataUrl(value) {
        return typeof value === 'string' && value.indexOf('data:') === 0;
    }

    // Holds the short server-side path for each uploaded image
    // field ('photo' / 'logo' / 'sign'). Pre-filled from whatever's
    // already saved on this card, EXCEPT when that saved value is
    // itself a base64 data URL — that legacy value is never reused,
    // it only gets migrated to a real upload (see below).
    const uploadedImagePaths = {
        photo: isDataUrl(legacyStoredImages.photo) ? null : legacyStoredImages.photo,
        logo:  isDataUrl(legacyStoredImages.logo)  ? null : legacyStoredImages.logo,
        sign:  isDataUrl(legacyStoredImages.sign)  ? null : legacyStoredImages.sign
    };

    // Route that stores one uploaded image (photo/logo/sign) for
    // this ID card and returns { success, path, url }.
    // `path`  -> short value to persist in the layout JSON (e.g. "idcards/photo_12.jpg")
    // `url`   -> full public URL to show in the <img> preview
    const FIELD_IMAGE_UPLOAD_ENDPOINT = "{{ route('event-id-cards.field-image', $idCardData->id) }}";

    function uploadCardImage(file, fieldKey, targetElId, statusElId) {

        const formData = new FormData();
        formData.append('image', file);
        formData.append('field', fieldKey);
        formData.append('_token', csrfToken);

        if (statusElId && document.getElementById(statusElId)) {
            $('#' + statusElId).html('<span style="color:#666;">Uploading...</span>');
        }

        $.ajax({

            url: FIELD_IMAGE_UPLOAD_ENDPOINT,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {

                if (response && response.success) {

                    // Store the SHORT path — this is what goes into
                    // the layout JSON when the card is saved.
                    uploadedImagePaths[fieldKey] = response.path || '';

                    // Swap the local base64 preview for the real,
                    // short server URL so nothing large lingers in
                    // the DOM either.
                    const targetEl = document.getElementById(targetElId);
                    if (targetEl && response.url) {
                        targetEl.src = response.url + '?' + Date.now();
                    }

                    if (statusElId && document.getElementById(statusElId)) {
                        $('#' + statusElId).html('<span style="color:green;">Uploaded</span>');
                    }

                } else {
                    if (statusElId && document.getElementById(statusElId)) {
                        $('#' + statusElId).html(
                            '<span style="color:#c0392b;">' +
                            ((response && response.message) || 'Upload failed') +
                            '</span>'
                        );
                    }
                }
            },

            error: function () {
                if (statusElId && document.getElementById(statusElId)) {
                    $('#' + statusElId).html('<span style="color:#c0392b;">Upload failed</span>');
                }
            }
        });
    }

    function wireImageUpload(inputId, targetElId, fieldKey, statusElId) {

        const input = document.getElementById(inputId);

        if (!input) {
            return;
        }

        input.addEventListener('change', function (e) {

            const file = e.target.files[0];

            if (!file) {
                return;
            }

            // 1) Instant local preview (temporary base64 data URL —
            //    this NEVER gets saved, it's just so the user sees
            //    the image immediately while the upload runs).
            normalizeImage(file, function (dataUrl) {
                document.getElementById(targetElId).src = dataUrl;
            });

            // 2) Upload the real file right away so we get back a
            //    short path to store instead of the base64 string.
            uploadCardImage(file, fieldKey, targetElId, statusElId);
        });
    }

    wireImageUpload('photoUpload', 'elPhoto', 'photo', 'photoUploadStatus');
    wireImageUpload('logoUpload', 'elLogo', 'logo', 'logoUploadStatus');
    wireImageUpload('signUpload', 'elSign', 'sign', 'signUploadStatus');

    // ---------------------------------------------------------------
    // ONE-TIME MIGRATION for cards saved before this fix
    // ---------------------------------------------------------------
    // If `legacyStoredImages.*` is still a base64 data URL, convert
    // it to a real file and upload it now, so the NEXT save writes a
    // short path instead of quietly re-saving the same huge string.
    function dataUrlToFile(dataUrl, filename) {
        const parts = dataUrl.split(',');
        const mimeMatch = parts[0].match(/:(.*?);/);
        const mime = mimeMatch ? mimeMatch[1] : 'image/jpeg';
        const binary = atob(parts[1]);
        const bytes = new Uint8Array(binary.length);
        for (let i = 0; i < binary.length; i++) {
            bytes[i] = binary.charCodeAt(i);
        }
        return new File([bytes], filename, { type: mime });
    }

    [
        { key: 'photo', elId: 'elPhoto', statusId: 'photoUploadStatus' },
        { key: 'logo', elId: 'elLogo', statusId: 'logoUploadStatus' },
        { key: 'sign', elId: 'elSign', statusId: 'signUploadStatus' }
    ].forEach(function (f) {
        const legacyValue = legacyStoredImages[f.key];
        if (!isDataUrl(legacyValue)) return;

        try {
            const file = dataUrlToFile(legacyValue, f.key + '.jpg');
            uploadCardImage(file, f.key, f.elId, f.statusId);
        } catch (err) {
            console.error('Could not migrate legacy ' + f.key + ' image:', err);
        }
    });


    // =========================================================
    // DRAG AND DROP
    // =========================================================

    let dragEl = null;
    let offX = 0;
    let offY = 0;

    function xyControlsFor(elId) {
        return fields.find(function (f) {
            return f.el === elId;
        });
    }

    function attachDrag(el) {

        el.addEventListener('mousedown', function (e) {

            dragEl = el;
            el.classList.add('dragging');

            const rect = card.getBoundingClientRect();
            const scale = rect.width / CARD_W;

            offX = (e.clientX - rect.left) / scale - parseFloat(el.style.left || 0);
            offY = (e.clientY - rect.top) / scale - parseFloat(el.style.top || 0);

            e.preventDefault();
        });
    }

    document.querySelectorAll('.el, .background-area').forEach(attachDrag);

    document.addEventListener('mousemove', function (e) {

        if (!dragEl) {
            return;
        }

        const rect = card.getBoundingClientRect();
        const scale = rect.width / CARD_W;

        let nx = Math.round((e.clientX - rect.left) / scale - offX);
        let ny = Math.round((e.clientY - rect.top) / scale - offY);

        nx = Math.max(0, Math.min(CARD_W, nx));
        ny = Math.max(0, Math.min(CARD_H, ny));

        dragEl.style.left = nx + 'px';
        dragEl.style.top = ny + 'px';

        const f = xyControlsFor(dragEl.id);

        if (f) {
            if (f.x) document.getElementById(f.x).value = nx;
            if (f.y) document.getElementById(f.y).value = ny;
        }

        if (dragEl.classList.contains('background-area')) {

            const area = backgroundAreas.find(function (item) {
                return item.id === dragEl.id;
            });

            if (area) {
                area.x = nx;
                area.y = ny;

                const panel = document.querySelector('.background-area-control[data-area-id="' + area.id + '"]');

                if (panel) {
                    const xInput = panel.querySelector('.background-area-x');
                    const yInput = panel.querySelector('.background-area-y');
                    if (xInput) xInput.value = nx;
                    if (yInput) yInput.value = ny;
                }
            }
        }

        const cloneMatch = fieldClones.find(function (c) {
            return c.id === dragEl.id;
        });

        if (cloneMatch) {

            const panel = document.querySelector('.field-clone-control[data-clone-id="' + cloneMatch.id + '"]');

            if (panel) {
                const xI = panel.querySelector('.clone-x');
                const yI = panel.querySelector('.clone-y');
                if (xI) xI.value = nx;
                if (yI) yI.value = ny;
            }
        }
    });

    document.addEventListener('mouseup', function () {
        if (dragEl) {
            dragEl.classList.remove('dragging');
        }
        dragEl = null;
    });


    // =========================================================
    // DUPLICATE / CLONE FIELDS
    // =========================================================

    let fieldClones = [];

    function generateCloneId(baseKey) {
        return baseKey + '_copy_' + Date.now() + '_' + Math.floor(Math.random() * 10000);
    }

    function rgbToHex(rgb) {

        if (!rgb) return '#1f2430';
        if (rgb.startsWith('#')) return rgb;

        const m = rgb.match(/\d+/g);
        if (!m) return '#1f2430';

        return '#' + m.slice(0, 3).map(function (x) {
            const h = parseInt(x, 10).toString(16);
            return h.length === 1 ? '0' + h : h;
        }).join('');
    }

    function addDuplicateButtons() {

        fields.forEach(function (f) {

            const anchorId = f.toggle || f.x || f.text;
            const anchorInput = document.getElementById(anchorId);

            if (!anchorInput) return;

            const grp = anchorInput.closest('.group');
            if (!grp) return;

            const titleRight = grp.querySelector('.group-title-right');
            if (!titleRight || titleRight.querySelector('.dup-btn')) return;

            const btn = document.createElement('button');

            btn.type = 'button';
            btn.className = 'dup-btn';
            btn.title = 'Duplicate this field';
            btn.textContent = '⎘';
            btn.style.cssText = 'background:none;border:none;cursor:pointer;font-size:14px;color:var(--accent);padding:0 2px;';

            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                addFieldClone(f.key);
            });

            titleRight.insertBefore(btn, titleRight.firstChild);
        });
    }

    function addFieldClone(baseKey, preset) {

        const f = fields.find(function (x) {
            return x.key === baseKey;
        });

        if (!f) return;

        const baseEl = document.getElementById(f.el);
        if (!baseEl) return;

        const cloneId = (preset && preset.id) || generateCloneId(baseKey);
        const isImage = !f.text;

        let el;

        if (isImage) {
            el = document.createElement('img');
            el.src = (preset && preset.src) || baseEl.src;
            el.className = baseEl.className;
        } else {
            el = document.createElement('div');
            el.className = baseEl.className;
            el.textContent = (preset && preset.text) || baseEl.textContent;
        }

        el.id = cloneId;
        el.style.position = 'absolute';

        el.style.left = ((preset && preset.x !== undefined) ? preset.x : (parseFloat(baseEl.style.left) || 0) + 15) + 'px';
        el.style.top = ((preset && preset.y !== undefined) ? preset.y : (parseFloat(baseEl.style.top) || 0) + 15) + 'px';

        if (isImage) {
            el.style.width = ((preset && preset.width) || parseFloat(baseEl.style.width) || 80) + 'px';
            el.style.height = ((preset && preset.height) || parseFloat(baseEl.style.height) || 80) + 'px';
        } else {
            el.style.fontSize = ((preset && preset.fontSize) || parseFloat(baseEl.style.fontSize) || 14) + 'px';
            el.style.color = (preset && preset.color) || baseEl.style.color || '#1f2430';
            el.style.fontWeight = (preset && preset.fontWeight) || baseEl.style.fontWeight || '400';
        }

        if (preset && preset.visible === false) {
            el.style.display = 'none';
        }

        card.appendChild(el);
        attachDrag(el);

        const cloneMeta = { id: cloneId, baseKey: baseKey, isImage: isImage };
        fieldClones.push(cloneMeta);

        createCloneControl(f, cloneMeta, el);
    }

    function createCloneControl(f, cloneMeta, el) {

        const anchorId = f.toggle || f.x || f.text;
        const anchorInput = document.getElementById(anchorId);
        const grp = anchorInput ? anchorInput.closest('.group') : null;
        const body = grp ? grp.querySelector('.group-body') : null;

        if (!body) return;

        const wrapper = document.createElement('div');
        wrapper.className = 'field-clone-control';
        wrapper.dataset.cloneId = cloneMeta.id;
        wrapper.style.cssText = 'border:1px dashed #d5d8dd;border-radius:6px;padding:8px;margin-top:6px;background:#fbfbfc;';

        const header = document.createElement('div');
        header.style.cssText = 'display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;';
        header.innerHTML = '<strong style="font-size:11px;color:var(--maroon-dark);">Copy of ' + f.key + '</strong>';

        const delBtn = document.createElement('button');
        delBtn.type = 'button';
        delBtn.textContent = 'Delete';
        delBtn.style.cssText = 'background:#dc3545;color:#fff;border:none;border-radius:4px;padding:3px 7px;font-size:10px;cursor:pointer;';

        delBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            el.remove();
            wrapper.remove();
            fieldClones = fieldClones.filter(function (c) {
                return c.id !== cloneMeta.id;
            });
        });

        header.appendChild(delBtn);
        wrapper.appendChild(header);

        if (!cloneMeta.isImage) {
            const textField = document.createElement('div');
            textField.className = 'field';
            textField.innerHTML = '<label>Text</label><input type="text" class="clone-text" value="' +
                (el.textContent || '').replace(/"/g, '&quot;') + '">';
            wrapper.appendChild(textField);
        } else {
            const uploadLabel = document.createElement('label');
            uploadLabel.className = 'filebtn';
            uploadLabel.innerHTML = 'Click to upload image<input type="file" class="clone-upload" accept="image/*">';
            wrapper.appendChild(uploadLabel);
        }

        const row4 = document.createElement('div');
        row4.className = 'row4';
        row4.innerHTML =
            '<div class="field"><label>X</label><input type="number" class="clone-x" value="' + (parseFloat(el.style.left) || 0) + '"></div>' +
            '<div class="field"><label>Y</label><input type="number" class="clone-y" value="' + (parseFloat(el.style.top) || 0) + '"></div>' +
            (cloneMeta.isImage
                ? '<div class="field"><label>W</label><input type="number" class="clone-w" value="' + (parseFloat(el.style.width) || 0) + '"></div>' +
                  '<div class="field"><label>H</label><input type="number" class="clone-h" value="' + (parseFloat(el.style.height) || 0) + '"></div>'
                : '<div class="field"><label>Size</label><input type="number" class="clone-size" value="' + (parseFloat(el.style.fontSize) || 14) + '"></div>');

        wrapper.appendChild(row4);

        if (!cloneMeta.isImage) {
            const row2 = document.createElement('div');
            row2.className = 'row2';
            row2.innerHTML =
                '<div class="field"><label>Color</label><input type="color" class="clone-color" value="' + rgbToHex(el.style.color) + '"></div>' +
                '<div class="field"><label>Weight</label><select class="clone-weight">' +
                '<option value="700"' + (el.style.fontWeight === '700' ? ' selected' : '') + '>Bold</option>' +
                '<option value="400"' + (el.style.fontWeight !== '700' ? ' selected' : '') + '>Normal</option></select></div>';
            wrapper.appendChild(row2);
        }

        body.appendChild(wrapper);

        const xInput = wrapper.querySelector('.clone-x');
        const yInput = wrapper.querySelector('.clone-y');

        xInput.addEventListener('input', function () {
            el.style.left = (parseFloat(xInput.value) || 0) + 'px';
        });

        yInput.addEventListener('input', function () {
            el.style.top = (parseFloat(yInput.value) || 0) + 'px';
        });

        if (cloneMeta.isImage) {

            const wInput = wrapper.querySelector('.clone-w');
            const hInput = wrapper.querySelector('.clone-h');

            wInput.addEventListener('input', function () {
                el.style.width = (parseFloat(wInput.value) || 0) + 'px';
            });

            hInput.addEventListener('input', function () {
                el.style.height = (parseFloat(hInput.value) || 0) + 'px';
            });

            const uploadInput = wrapper.querySelector('.clone-upload');

            uploadInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;

                normalizeImage(file, function (dataUrl) {
                    el.src = dataUrl;
                });
            });

        } else {

            const textInput = wrapper.querySelector('.clone-text');
            const sizeInput = wrapper.querySelector('.clone-size');
            const colorInput = wrapper.querySelector('.clone-color');
            const weightInput = wrapper.querySelector('.clone-weight');

            textInput.addEventListener('input', function () {
                el.textContent = textInput.value;
            });

            sizeInput.addEventListener('input', function () {
                el.style.fontSize = (parseFloat(sizeInput.value) || 14) + 'px';
            });

            colorInput.addEventListener('input', function () {
                el.style.color = colorInput.value;
            });

            weightInput.addEventListener('change', function () {
                el.style.fontWeight = weightInput.value;
            });
        }
    }

    addDuplicateButtons();


    // =========================================================
    // COLLAPSIBLE GROUPS
    // =========================================================

    document.querySelectorAll('.group-title').forEach(function (title) {
        title.addEventListener('click', function () {
            title.parentElement.classList.toggle('collapsed');
        });
    });


    // =========================================================
    // ZOOM
    // =========================================================

    const zoom = document.getElementById('zoom');
    const zoomVal = document.getElementById('zoomVal');

    if (zoom) {
        zoom.value = 100;
        zoomVal.textContent = '100%';
        card.style.transform = 'scale(1)';

        zoom.addEventListener('input', function () {
            const scale = zoom.value / 100;
            card.style.transform = 'scale(' + scale + ')';
            zoomVal.textContent = zoom.value + '%';
        });
    }


    // =========================================================
    // RESET
    // =========================================================

    const defaults = {};

    document.querySelectorAll('.controls input, .controls select').forEach(function (input) {
        defaults[input.id] = input.type === 'checkbox' ? input.checked : input.value;
    });

    const resetBtn = document.getElementById('resetBtn');

    if (resetBtn) {
        resetBtn.addEventListener('click', function () {

            Object.keys(defaults).forEach(function (id) {
                const input = document.getElementById(id);
                if (!input) return;

                if (input.type === 'checkbox') {
                    input.checked = defaults[id];
                } else {
                    input.value = defaults[id];
                }
            });

            fields.forEach(applyField);
            updateQr();
        });
    }


    // =========================================================
    // BUILD LAYOUT JSON
    // =========================================================

    function buildLayoutJSON() {

        const layout = {
            cardWidth: CARD_W,
            cardHeight: CARD_H,
            background: card.style.backgroundImage || '',
            fields: {},
            tabledata: document.getElementById('tabledataTextarea') ? document.getElementById('tabledataTextarea').value : '',
            useval: document.getElementById('usevalTextarea') ? document.getElementById('usevalTextarea').value : '',
            tablePosition: {
                left: parseFloat(document.getElementById('tableLeft')?.value) || 30,
                top: parseFloat(document.getElementById('tableTop')?.value) || 120,
                width: parseFloat(document.getElementById('tableWidth')?.value) || Math.max(120, CARD_W - 60),
                height: parseFloat(document.getElementById('tableHeight')?.value) || 0
            }
        };

        fields.forEach(function (f) {

            if (f.toggle) {
                const toggle = document.getElementById(f.toggle);
                if (!toggle || !toggle.checked) {
                    return;
                }
            }

            const el = document.getElementById(f.el);
            if (!el) return;

            const item = {
                label: getFieldLabel(f.key),
                x: parseFloat(el.style.left) || 0,
                y: parseFloat(el.style.top) || 0,
                visible: true
            };

            if (f.w) item.width = parseFloat(el.style.width) || 0;
            if (f.h) item.height = parseFloat(el.style.height) || 0;
            if (f.radius) item.borderRadius = parseFloat(el.style.borderRadius) || 0;

            if (f.text) {
                const input = document.getElementById(f.text);
                if (input) item.text = input.value;
            }

            if (f.size) item.fontSize = parseFloat(el.style.fontSize) || 12;
            if (f.color) item.color = el.style.color || '';
            if (f.weight) item.fontWeight = el.style.fontWeight || '400';

            item.type = f.text ? 'text' : 'image';

            if (item.type === 'image') {
                try {
                    // Prefer the short server-side path that
                    // uploadCardImage() stored for this field.
                    // Only ever fall back to el.src (and only if it
                    // isn't a giant base64 data URL) so a huge
                    // string can never reach the database.
                    const storedPath = uploadedImagePaths[f.key];

                    if (storedPath) {
                        item.src = storedPath;
                    } else if (el.src && el.src.indexOf('data:') !== 0) {
                        item.src = el.src;
                    } else {
                        item.src = '';
                    }
                } catch (err) {
                    item.src = '';
                }
            }

            try {
                const cssEl = document.getElementById(f.key + 'Css');
                item.css = cssEl ? cssEl.value : '';
            } catch (err) {
                item.css = '';
            }

            layout.fields[f.key] = item;
        });

        backgroundAreas.forEach(function (area) {

            const el = document.getElementById(area.id);
            if (!el) return;

            const visible = el.style.display !== 'none';

            layout.fields[area.id] = {
                id: area.id,
                name: area.name || 'Background Area',
                type: 'shape',
                x: parseFloat(el.style.left) || 0,
                y: parseFloat(el.style.top) || 0,
                width: parseFloat(el.style.width) || 0,
                height: parseFloat(el.style.height) || 0,
                backgroundColor: el.style.backgroundColor || area.backgroundColor || '#9e1b32',
                opacity: el.style.opacity === '' ? 1 : parseFloat(el.style.opacity),
                borderRadius: parseFloat(el.style.borderRadius) || 0,
                visible: visible
            };
        });

        fieldClones.forEach(function (c) {

            const el = document.getElementById(c.id);
            if (!el) return;

            const item = {
                cloneOf: c.baseKey,
                type: c.isImage ? 'image' : 'text',
                x: parseFloat(el.style.left) || 0,
                y: parseFloat(el.style.top) || 0,
                visible: el.style.display !== 'none'
            };

            if (c.isImage) {
                item.width = parseFloat(el.style.width) || 0;
                item.height = parseFloat(el.style.height) || 0;

                // Same rule as the base image fields: never persist
                // a base64 data URL. A clone usually just mirrors
                // its base field's image, so fall back to that
                // field's uploaded (short) path when the clone
                // itself doesn't have its own uploaded path.
                const cloneStoredPath = uploadedImagePaths[c.id] || uploadedImagePaths[c.baseKey];

                if (cloneStoredPath) {
                    item.src = cloneStoredPath;
                } else if (el.src && el.src.indexOf('data:') !== 0) {
                    item.src = el.src;
                } else {
                    item.src = '';
                }
            } else {
                item.text = el.textContent || '';
                item.fontSize = parseFloat(el.style.fontSize) || 14;
                item.color = el.style.color || '';
                item.fontWeight = el.style.fontWeight || '400';
            }

            layout.fields[c.id] = item;
        });

        return layout;
    }


    // =========================================================
    // EXPORT LAYOUT (download as JSON file)
    // =========================================================

    function wireExportButton(id) {

        const btn = document.getElementById(id);

        if (!btn) return;

        btn.addEventListener('click', function () {

            const layout = buildLayoutJSON();

            const blob = new Blob([JSON.stringify(layout, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);

            const a = document.createElement('a');
            a.href = url;
            a.download = 'idcard-layout.json';
            a.click();

            URL.revokeObjectURL(url);
        });
    }

    wireExportButton('exportLayoutBtn');
    wireExportButton('exportLayoutBtnFloat');


    // =========================================================
    // SAVE ID CARD (POST layout JSON to your server)
    // =========================================================

        // =========================================================
    // SAVE ID CARD
    // Sends the layout JSON + (optionally) the background image
    // file in one multipart request. The controller stores the
    // file and saves only its path in the `background` column.
    // =========================================================

    function wireSaveButton(id) {

        const btn = document.getElementById(id);

        if (!btn) return;

        btn.addEventListener('click', async function () {

            try {

                const layout = buildLayoutJSON();

                const formData = new FormData();

                formData.append('name', 'Default ID Card');
                formData.append('card_width', CARD_W);
                formData.append('card_height', CARD_H);
                formData.append('layout', JSON.stringify(layout));

                if (sampleId) {
                    formData.append('sample_id', sampleId);
                }

                if (eventId) {
                    formData.append('event_id', eventId);
                }

                if (sampleBackground) {
                    formData.append('background_path', sampleBackground);
                }

                if (selectedBackgroundFile) {
                    formData.append('background', selectedBackgroundFile);
                }

                console.log('LAYOUT BEING SAVED:', layout);

                btn.disabled = true;
                const originalLabel = btn.innerHTML;
                btn.innerHTML = '⏳ Saving...';

                const response = await fetch(SAVE_ENDPOINT, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const result = await response.json().catch(function () { return {}; });

                console.log('SERVER RESPONSE:', result);

                if (response.ok && result.success !== false) {
                    alert(result.message || 'ID Card saved successfully.');
                } else {
                    alert(result.message || 'Unable to save ID Card.');
                }

            } catch (error) {

                console.error('SAVE ERROR:', error);
                alert('Could not reach the server.');

            } finally {

                btn.disabled = false;
                btn.innerHTML = '💾 Save ID Card';
            }
        });
    }

    wireSaveButton('saveLayoutBtn');
    wireSaveButton('saveLayoutBtnFloat');

    // wireSaveButton('saveLayoutBtn');
    // wireSaveButton('saveLayoutBtnFloat');


    // =========================================================
    // DOWNLOAD PNG
    // =========================================================

    function wireDownloadButton(id) {

        const btn = document.getElementById(id);

        if (!btn) return;

        btn.addEventListener('click', function () {

            const previousTransform = card.style.transform;
            card.style.transform = 'none';

            html2canvas(card, { scale: 3, useCORS: true, backgroundColor: null })
                .then(function (canvas) {

                    card.style.transform = previousTransform;

                    const link = document.createElement('a');
                    link.download = 'id-card.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                })
                .catch(function (error) {
                    card.style.transform = previousTransform;
                    console.error(error);
                    alert('Could not export image.');
                });
        });
    }

    wireDownloadButton('downloadBtn');
    wireDownloadButton('downloadBtnFloat');

})();

@php
    // Turn a stored value (short relative path, full URL, or legacy
    // base64 data URL from before this fix) into something safe to
    // drop straight into an <img src>.
    $resolveCardImageUrl = function ($value) {
        if (!$value) {
            return '';
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, 'data:')) {
            return $value;
        }
        return asset('storage/' . ltrim($value, '/'));
    };

    $initialPhotoUrl = $resolveCardImageUrl($designcard->layout['fields']['photo']['src'] ?? '');
    $initialLogoUrl  = $resolveCardImageUrl($designcard->layout['fields']['logo']['src'] ?? '');
    $initialSignUrl  = $resolveCardImageUrl($designcard->layout['fields']['sign']['src'] ?? '');
@endphp

document.getElementById('elPhoto').src = @json($initialPhotoUrl);
document.getElementById('elLogo').src = @json($initialLogoUrl);
if (document.getElementById('elSign') && @json($initialSignUrl)) {
    document.getElementById('elSign').src = @json($initialSignUrl);
}

</script>



@endsection