@extends('frontend.layout.applayout')
@section('title', 'ID Card Editor — ' . ucwords($school->school_name ?? ''))
@section('content')
@php
  //echo '<pre>'; print_r($selectedSample); die;
  $editorBackground = $designcard->background ?? ($selectedSample->file_path ?? null);
  $editorBackgroundUrl = $editorBackground
      ? (preg_match('/^https?:\\/\\//', $editorBackground)
          ? $editorBackground
          : asset('storage/' . $editorBackground))
      : '';


    $mmToPx = 96 / 25.4;

	$cardWidth = !empty($selectedSample->width)
	    ? round($selectedSample->width * $mmToPx)
	    : ($orientation === 'vertical' ? 204 : 317);

	$cardHeight = !empty($selectedSample->height)
	    ? round($selectedSample->height * $mmToPx)
	    : ($orientation === 'vertical' ? 317 : 204);
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

  /* =========== CONTROLS (now on the RIGHT, scrolls with the page) =========== */
  .controls{
    order:2;
    width:460px;
    background:var(--panel-bg);
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,.08);
    padding:6px 0 16px;
    /* flex-shrink:0; */
  }
  .group{
    border-bottom:1px solid var(--line);
    padding:14px 18px;
  }
  .group:last-child{border-bottom:none;}

  .layout-tabs{
    display:flex;
    gap:8px;
    margin-bottom:12px;
    border-bottom:1px solid var(--line);
    padding-bottom:10px;
  }
  .layout-tab-btn{
    flex:1;
    border:1px solid #d5d8dd;
    background:#f8fafc;
    color:var(--muted);
    border-radius:6px;
    padding:7px 10px;
    font-size:12px;
    font-weight:700;
    cursor:pointer;
  }
  .layout-tab-btn.active{
    background:var(--maroon);
    border-color:var(--maroon);
    color:#fff;
  }
  .layout-tab-panel{display:none;}
  .layout-tab-panel.active{display:block;}
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

  /* ---- visibility toggle switch ---- */
  .switch{
    position:relative;
    display:inline-block;
    width:34px;
    height:19px;
    flex-shrink:0;
  }
  .switch input{
    opacity:0;
    width:0;
    height:0;
  }
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

  .group-title-left{
    display:flex;
    align-items:center;
    gap:9px;
  }
  .group-title-right{
    display:flex;
    align-items:center;
    gap:10px;
  }
  .group.field-off .group-title h3{color:var(--muted);}
  .group.field-off .el-preview-note{color:var(--muted);}

  .toggle-all-row{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:12px 18px;
    border-bottom:1px solid var(--line);
    background:#fafbfc;
  }
  .toggle-all-row span{
    font-size:12.5px;
    font-weight:700;
    color:var(--maroon-dark);
  }
  .toggle-all-row .links{
    display:flex;
    gap:10px;
  }
  .toggle-all-row .links button{
    font-size:11px;
    color:var(--accent);
    background:none;
    border:none;
    cursor:pointer;
    text-decoration:underline;
    padding:0;
  }

  /* =========== IMAGE CANVAS (now on the LEFT, stays static on scroll) =========== */
  .preview-wrap{
    order:1;
    flex:1;
    /* min-width:340px; */
    display:flex;
    flex-direction:column;
    align-items:center;
    gap:16px;
    position:sticky;
    top:24px;
    align-self:flex-start;
  }
  .zoom-controls{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:12px;
    color:var(--muted);
  }
  .zoom-controls input{vertical-align:middle;}

  .card-stage{
   /* background:repeating-conic-gradient(#e9eaed 0% 25%, #f6f7f8 0% 50%) 50% / 20px 20px;*/
    padding:40px;
    border-radius:12px;
  }

    .id-card{
        position:relative;
        background-image: url('{{ $editorBackgroundUrl }}');
        background-size:100% 100%;
        background-repeat:no-repeat;
        background-position:center;
        overflow:hidden;
        box-shadow:0 8px 24px rgba(0,0,0,.25);
        border-radius:6px;
        transform-origin:top center;
    }

  .el{
    position:absolute;
    cursor:move;
    outline:1px dashed transparent;
    z-index:2;
  }
  .el:hover{outline-color:rgba(47,111,237,.6);}
  .el.dragging{outline-color:var(--accent);z-index:50;}

  .el-photo{
    object-fit:cover;
    border:3px solid var(--maroon);
    background:#eee;
  }
  .el-text{
    display:block;
    white-space:normal;
    word-break:break-word;
    overflow-wrap:anywhere;
    max-width:calc(100% - 20px);
    line-height:1.25;
  }
  .el-tabledata{
    position:absolute;
    z-index:3;
    display:block;
    background:transparent;
    border:none;
    border-radius:0;
    padding:0;
    box-shadow:none;
    color:var(--ink);
    font-size:12px;
    line-height:1.25;
    overflow:auto;
    pointer-events:none;
    width:auto;
    min-width:0;
    max-width:none;
    box-sizing:border-box;
  }
  .el-tabledata table{
    width:100%;
    min-width:100%;
    height:100%;
    border-collapse:collapse;
  }
  .el-tabledata th,
  .el-tabledata td{
    border:1px solid rgba(31,36,48,0.35);
    padding:3px 5px;
    text-align:left;
    vertical-align:top;
  }
  .el-qr{
    object-fit:contain;
  }
  .el-logo{
    object-fit:contain;
    background:transparent;
  }
  .el-sign{
    object-fit:contain;
    background:transparent;
  }

  /* =========================================
     BACKGROUND AREAS / SHAPES
  ========================================= */

  .background-area{
    position:absolute;
    cursor:move;
    box-sizing:border-box;
    outline:1px dashed transparent;
    user-select:none;
  }

  .background-area:hover{
    outline-color:rgba(47,111,237,.75);
  }

  .background-area.dragging{
    outline:2px dashed var(--accent);
  }

  .hint{
    font-size:12px;
    color:var(--muted);
    text-align:center;
    max-width:700px;
  }

  /* =========== ALWAYS-VISIBLE ACTION TOOLBAR =========== */
  .floating-toolbar{
    position:fixed;
    left:42%;
    bottom:22px;
    transform:translateX(-50%);
    z-index:1000;
    display:flex;
    align-items:center;
    gap:6px;
    background:#fff;
    padding:10px 10px;
    border-radius:40px;
    box-shadow:0 6px 22px rgba(0,0,0,.22);
  }

    .field-css{
        width:100%;
        min-height:44px;
        padding:6px 8px;
        border:1px solid #d5d8dd;
        border-radius:6px;
        font-size:12px;
        font-family:Consolas,monospace;
        background:#ffffff;
        resize:vertical;
    }

  ::-webkit-scrollbar{width:8px;}
  ::-webkit-scrollbar-thumb{background:#c9ccd1;border-radius:4px;}

  /* =========================================================
     MOBILE / RESPONSIVE FIXES ONLY
     (no functional changes — layout/sizing adjustments so the
     existing editor fits and stays usable on small screens)
  ========================================================= */
  @media (max-width: 991px){
    .editor{
      padding:16px;
      gap:16px;
    }
    .controls{
      width:100%;
    }
    .preview-wrap{
      width:100%;
      position:static;
      top:auto;
    }
  }

  @media (max-width: 767px){
    html, body{
      overflow-x:hidden;
    }
    .id-editor-header-row{
      flex-wrap:wrap;
      gap:10px;
    }
    .id-editor-header-actions{
      flex-wrap:wrap;
      width:100%;
      justify-content:flex-start;
      gap:6px;
    }
    .id-editor-header-actions .btn{
      margin:0 !important;
      font-size:12px;
      padding:6px 10px;
    }
    .editor{
      padding:12px;
      gap:14px;
    }
    .controls{
      border-radius:8px;
    }
    .group{
      padding:12px 14px;
    }
    .row4{
      gap:6px;
    }
    .row4 .field{
      min-width:64px;
    }
    .card-stage{
      padding:20px;
      max-width:100%;
      overflow-x:auto;
    }
    .zoom-controls{
      flex-wrap:wrap;
      justify-content:center;
      text-align:center;
    }
    .hint{
      max-width:100%;
      padding:0 8px;
    }
    .floating-toolbar{
      left:50%;
      bottom:12px;
      max-width:94vw;
      flex-wrap:wrap;
      justify-content:center;
      padding:8px;
      gap:6px;
    }
    .floating-toolbar .btn{
      font-size:12px;
      padding:6px 10px;
      margin:0 !important;
    }
  }

  @media (max-width: 420px){
    .id-editor-header-actions .btn{
      font-size:11px;
      padding:5px 8px;
    }
    .card-stage{
      padding:12px;
    }
    .row4 .field{
      min-width:56px;
    }
    .field input[type="text"],
    .field input[type="number"],
    .field select{
      font-size:16px; /* prevents iOS auto-zoom on focus */
    }
  }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid mt-1">
            <!-- <div class="row">
               
            </div> -->
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card mt-4">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="d-flex align-items-center justify-content-between w-100 id-editor-header-row">
                            <div>
                                <h4 class="mb-0">ID Card Editor</h4>
                                <div class="sub">
                                    {{ ucwords($school->school_name ?? " ") }} · drag fields on the card or use the controls
                                </div>
                            </div>
                            <div class="d-flex align-items-center id-editor-header-actions">
                               
                                <button
                                    type="button"
                                    id="saveLayoutBtn"
                                    class="btn btn-primary btn-sm mx-2">
                                    💾 Save ID Card
                                </button>
                                <input
                                    type="file"
                                    id="importLayoutFile"
                                    accept="application/json"
                                    style="display:none;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="editor">

                    <!-- ===================== LEFT CONTROLS ===================== -->
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
                        <div class="group-title"><h3>Card Background</h3><span class="chev">▾</span></div>
                        <div class="group-body">
                            <div class="field">
                               <label for="orientationSelect">Orientation</label>
                               <select id="orientationSelect">
                                    <option value="horizontal"
                                        {{ ($orientation ?? 'vertical') === 'horizontal' ? 'selected' : '' }}>
                                        Horizontal
                                    </option>

                                    <option value="vertical"
                                        {{ ($orientation ?? 'vertical') === 'vertical' ? 'selected' : '' }}>
                                        Vertical
                                    </option>
                                </select>
                            </div>
                           <!--  <label class="filebtn">Upload card design (portrait or landscape)
                            <input type="file" id="bgUpload" accept="image/*">
                            </label> -->
                            <div style="font-size:11px;color:var(--muted);">The card keeps the standard 84 × 54 mm ratio — horizontal designs are landscape and vertical designs are portrait.</div>
                        </div>
                        </div>

                        <!-- BACKGROUND AREAS -->
                        <div class="group" id="backgroundAreasGroup">
                        <div class="group-title">
                            <h3>Background Areas</h3>
                            <div class="group-title-right">
                            <button
                                type="button"
                                id="addBackgroundAreaBtn"
                                style="
                                background:var(--maroon);
                                color:#fff;
                                border:none;
                                border-radius:5px;
                                padding:5px 9px;
                                font-size:11px;
                                font-weight:700;
                                cursor:pointer;
                                "
                            >+ Add Area</button>
                            <span class="chev">▾</span>
                            </div>
                        </div>

                        <div class="group-body" id="backgroundAreasContainer">
                            <div
                            id="backgroundAreasEmpty"
                            style="font-size:11px;color:var(--muted);"
                            >
                            Add a rectangle over the background and control its X, Y, width, height and color.
                            </div>
                        </div>
                        </div>

                        <div class="group">
                        <div class="group-title"><h3>Layout Data</h3><span class="chev">▾</span></div>
                        <div class="group-body">
                            <div class="layout-tabs">
                                <button type="button" class="layout-tab-btn active" data-tab="table-tab">Table</button>
                                <button type="button" class="layout-tab-btn" data-tab="inline-tab">Inline</button>
                            </div>

                            <div id="table-tab" class="layout-tab-panel active">

                                <div class="field">
                                    <label>Quick Build</label>
                                    <div class="row4" style="align-items:flex-end;">
                                        <div class="field" style="flex:0 0 64px;">
                                            <label>Rows</label>
                                            <input type="number" id="tableBuildRows" min="1" max="20" value="3">
                                        </div>
                                        <div class="field" style="flex:0 0 64px;">
                                            <label>Cols</label>
                                            <input type="number" id="tableBuildCols" min="1" max="10" value="2">
                                        </div>
                                        <div class="field" style="flex:1;">
                                            <label>&nbsp;</label>
                                            <button type="button" id="tableNewBtn" class="filebtn" style="border-style:solid;">＋ New Table</button>
                                        </div>
                                        <div class="field" style="flex:1;">
                                            <label>&nbsp;</label>
                                            <button type="button" id="tableLoadBtn" class="filebtn" style="border-style:solid;">↺ Load From HTML</button>
                                        </div>
                                    </div>
                                    <button type="button" id="tablePresetStudentBtn" class="filebtn" style="border-style:solid;margin-top:8px;">🎓 Insert Student Info Table (Name / Father / Class / DOB / Adm. No.)</button>
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
                                        <div class="field">
                                            <label>Border Px</label>
                                            <input type="number" id="tableBorderWidth" min="0" max="10" value="1">
                                        </div>
                                        <div class="field">
                                            <label>Border Color</label>
                                            <input type="color" id="tableBorderColor" value="#1f2430">
                                        </div>
                                        <div class="field">
                                            <label>Font Px</label>
                                            <input type="number" id="tableFontSize" min="6" max="30" value="12">
                                        </div>
                                    </div>

                                    <div class="row4" style="margin-top:8px;">
                                        <div class="field">
                                            <label>Padding Top</label>
                                            <input type="number" id="tablePaddingTop" min="0" max="60" value="3">
                                        </div>
                                        <div class="field">
                                            <label>Padding Right</label>
                                            <input type="number" id="tablePaddingRight" min="0" max="60" value="3">
                                        </div>
                                        <div class="field">
                                            <label>Padding Bottom</label>
                                            <input type="number" id="tablePaddingBottom" min="0" max="60" value="3">
                                        </div>
                                        <div class="field">
                                            <label>Padding Left</label>
                                            <input type="number" id="tablePaddingLeft" min="0" max="60" value="3">
                                        </div>
                                    </div>

                                    <div class="row4" style="margin-top:8px;">
                                        <div class="field">
                                            <label>Margin Top</label>
                                            <input type="number" id="tableMarginTop" min="0" max="60" value="0">
                                        </div>
                                        <div class="field">
                                            <label>Margin Right</label>
                                            <input type="number" id="tableMarginRight" min="0" max="60" value="0">
                                        </div>
                                        <div class="field">
                                            <label>Margin Bottom</label>
                                            <input type="number" id="tableMarginBottom" min="0" max="60" value="0">
                                        </div>
                                        <div class="field">
                                            <label>Margin Left</label>
                                            <input type="number" id="tableMarginLeft" min="0" max="60" value="0">
                                        </div>
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
                                            <label class="switch">
                                                <input type="checkbox" id="tableHeaderToggle" checked>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="field">
                                    <label>Visual Editor <span style="font-weight:400;text-transform:none;color:var(--muted);">(click any block to select it, then edit its text or style — a div and a table can both live here at once)</span></label>
                                    <div id="tableGridEditor" style="border:1px dashed #d5d8dd;border-radius:6px;padding:8px;overflow:auto;max-height:220px;background:#c1c3c4;"></div>
                                </div>

                                <div class="field">
                                    <label for="tabledataTextarea">Raw HTML / CSS (auto-synced with the visual editor above)</label>
                                    <textarea id="tabledataTextarea" rows="6" placeholder="Enter HTML table layout and CSS here" style="resize:both">{{ isset($designcard->layout['tabledata']) ? $designcard->layout['tabledata'] : ($designcard->tabledata ?? '') }}</textarea>
                                </div>

                                <div class="row4">
                                    <div class="field">
                                        <label>Left</label>
                                        <input type="number" id="tableLeft" value="{{ isset($designcard->layout['tablePosition']['left']) ? $designcard->layout['tablePosition']['left'] : 30 }}">
                                    </div>
                                    <div class="field">
                                        <label>Top</label>
                                        <input type="number" id="tableTop" value="{{ isset($designcard->layout['tablePosition']['top']) ? $designcard->layout['tablePosition']['top'] : 120 }}">
                                    </div>
                                    <div class="field">
                                        <label>Width</label>
                                        <input type="number" id="tableWidth" value="{{ isset($designcard->layout['tablePosition']['width']) ? $designcard->layout['tablePosition']['width'] : 180 }}">
                                    </div>
                                    <div class="field">
                                        <label>Height</label>
                                        <input type="number" id="tableHeight" value="{{ isset($designcard->layout['tablePosition']['height']) ? $designcard->layout['tablePosition']['height'] : 0 }}">
                                    </div>
                                </div>
                            </div>

                            <div id="inline-tab" class="layout-tab-panel">
                                <div class="field">
                                    <label for="usevalTextarea">Inline Value</label>
                                    <textarea id="usevalTextarea" rows="8" placeholder="Enter inline layout / value here">{{ isset($designcard->layout['useval']) ? $designcard->layout['useval'] : ($designcard->useval ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                        </div>

                        <!-- SCHOOL LOGO -->
                        <div class="group">
                        <div class="group-title"><h3>School Logo</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="logoToggle"  @if(isset($designcard->layout['fields']['logo'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <label class="filebtn">Click to upload logo
                            <input type="file" id="logoUpload" accept="image/*">
                            </label>
                            <div class="row4">
                            <div class="field"><label>X</label><input type="number" id="logoX" value="{{ $designcard->layout['fields']['logo']['x'] ?? 30 }}"></div>
                            <div class="field"><label>Y</label><input type="number" id="logoY" value="{{ $designcard->layout['fields']['logo']['y'] ?? 20 }}"></div>
                            <div class="field"><label>W</label><input type="number" id="logoW" value="{{ $designcard->layout['fields']['logo']['width'] ?? 30 }}"></div>
                            <div class="field"><label>H</label><input type="number" id="logoH" value="{{ $designcard->layout['fields']['logo']['height'] ?? 30 }}"></div>
                            </div>
                        </div>
                        </div>

                        <!-- SCHOOL NAME -->
                        <div class="group">
                        <div class="group-title"><h3>School Name</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="schoolNameToggle"  @if(isset($designcard->layout['fields']['schoolName'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <div class="field"><label>Text</label><input type="text" id="schoolNameText" value="{{ $designcard->layout['fields']['schoolName']['text'] ?? (ucwords($school->school_name) ?? '') }}"></div>
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
                        <div class="group-title"><h3>Address</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="addressToggle" @if(isset($designcard->layout['fields']['address'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
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
                        <div class="group-title"><h3>Student Address</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="studentAddressToggle" @if(isset($designcard->layout['fields']['studentAddress'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
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
                        <div class="group-title"><h3>Session</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="sessionToggle" @if(isset($designcard->layout['fields']['session'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <div class="field"><label>Text</label><input type="text" id="sessionText" value="Session: 2026-2027"></div>
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
                        <div class="group-title"><h3>Student Photo</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="photoToggle" @if(isset($designcard->layout['fields']['photo'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
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
                        <div class="group-title"><h3>Student Name</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="nameToggle" @if(isset($designcard->layout['fields']['name'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
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
                        <div class="group-title"><h3>Admission / Roll No</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="admToggle" @if(isset($designcard->layout['fields']['adm'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
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
                        <div class="group-title"><h3>Blood Group / Contact</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="bloodToggle" @if(isset($designcard->layout['fields']['blood'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
                        <div class="group-body">
                            <div class="field"><label>Text</label><input type="text" id="bloodText" value="Blood Group: O+  |  Ph: 98765 43210"></div>
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
                        <div class="group-title"><h3>Principal Signature</h3><div class="group-title-right"><label class="switch" onclick="event.stopPropagation()"><input type="checkbox" id="signToggle" @if(isset($designcard->layout['fields']['sign'])) checked @endif><span class="slider"></span></label><span class="chev">▾</span></div></div>
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

                    <!-- ===================== RIGHT PREVIEW ===================== -->
                    <div class="preview-wrap">

                        <div class="zoom-controls">
                        Zoom
                        <input type="range" id="zoom" min="50" max="150" value="100">
                        <span id="zoomVal">100%</span>
                        </div>

                        <div class="card-stage">
                            <div id="idCard" class="id-card"
                                data-vertical-sample="{{ isset($verticalSample->file_path) ? asset('storage/' . $verticalSample->file_path) : '' }}"
                                data-horizontal-sample="{{ isset($horizontalSample->file_path) ? asset('storage/' . $horizontalSample->file_path) : '' }}">

                            <img id="elLogo" class="el el-logo"
                                src="{{ isset($designcard->layout['fields']['logo']['src']) && $designcard->layout['fields']['logo']['src'] ? asset('storage/' . $designcard->layout['fields']['logo']['src']) : 'https://placehold.co/160x160/ffffff/9e1b32?text=Logo' }}" alt="School Logo">

                            <div id="elSchoolName" class="el el-text">Mother's Pride School</div>
                            <div id="elAddress" class="el el-text">123 Education Lane, Varanasi, UP - 221001</div>
                            <div id="elSession" class="el el-text">Session: 2026-2027</div>

                            <img id="elPhoto" class="el el-photo"
                                src="{{ isset($designcard->layout['fields']['photo']['src']) && $designcard->layout['fields']['photo']['src'] ? asset('storage/' . $designcard->layout['fields']['photo']['src']) : 'https://placehold.co/300x300/eeeeee/999999?text=Photo' }}" alt="Student Photo">
                            <div id="elStudentAddress" class="el el-text">Address: 24, Green Park, Varanasi, UP - 221001</div>
                            <div id="elTableData" class="el el-tabledata"></div>

                            <div id="elName" class="el el-text" style="{{@$designcard->layout['fields']['name']['css']}}">AARAV SHARMA</div>
                            <div id="elFather" class="el el-text">Father: Rakesh Sharma</div>
                            <div id="elMother" class="el el-text">Mother: Anita Sharma</div>
                            <div id="elClass" class="el el-text">Class: V - B</div>
                            <div id="elDob" class="el el-text">DOB: 12-05-2015</div>
                            <div id="elAdm" class="el el-text">Adm No: MP-2026-0143</div>
                            <div id="elBlood" class="el el-text">Blood Group: O+  |  Ph: 98765 43210</div>

                            <img id="elSign" class="el el-sign"
                                src="{{ isset($designcard->layout['fields']['sign']['src']) && $designcard->layout['fields']['sign']['src'] ? asset('storage/' . $designcard->layout['fields']['sign']['src']) : 'https://placehold.co/360x120/ffffff/1f2430?text=Signature' }}" alt="Principal Signature">

                            <img id="elQr" class="el el-qr"
                                src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=MP-2026-0143" alt="QR">

                        </div>
                        </div>

                        <div class="floating-toolbar">
                         
                            <button type="button" id="saveLayoutBtnFloat" class="btn btn-primary btn-sm mx-1">💾 Save ID Card</button>
                            
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

    // const HORIZONTAL_CARD_W = 317;
    // const HORIZONTAL_CARD_H = 204;
    // const VERTICAL_CARD_W = 204;
    // const VERTICAL_CARD_H = 317;

    const HORIZONTAL_CARD_W = {{ $cardWidth }};
    const HORIZONTAL_CARD_H = {{ $cardHeight }};

    const VERTICAL_CARD_W = {{ $cardWidth }};
    const VERTICAL_CARD_H = {{ $cardHeight }};



    let CARD_W = HORIZONTAL_CARD_W;
    let CARD_H = HORIZONTAL_CARD_H;


    // =========================================================
    // SET CARD SIZE
    // =========================================================

    function setCardSize(orientation) {

        const horizontal = orientation === 'horizontal';

        CARD_W = horizontal ? HORIZONTAL_CARD_W : VERTICAL_CARD_W;
        CARD_H = horizontal ? HORIZONTAL_CARD_H : VERTICAL_CARD_H;

        card.style.width = CARD_W + 'px';
        card.style.height = CARD_H + 'px';

        const sampleUrl = horizontal
            ? card.dataset.horizontalSample
            : card.dataset.verticalSample;

        if (sampleUrl) {
            card.style.backgroundImage = `url("${sampleUrl}")`;
        }
    }


    // Default card size
    const orientationSelect =
        document.getElementById('orientationSelect');

    setCardSize(orientationSelect.value);

    orientationSelect.addEventListener(
        'change',
        function () {
            const url = new URL(window.location.href);
            url.searchParams.set('orientation', orientationSelect.value);
            window.location.href = url.toString();
        }
    );

    // initial layout from server (may be empty)
    const initialLayout = @json($designcard->layout ?? (object)[]);

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
    // Drives the tabledataTextarea from a live editable <table>
    // so the raw HTML box stays in sync automatically.
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

        // Individual per-side padding / margin controls (Top / Right / Bottom / Left)
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

        // The grid editor can now hold MORE THAN ONE top-level block at once
        // (e.g. a <div> heading AND a <table> below it). `activeElement` is
        // whichever block is currently selected — that's what the Style
        // panel (border/padding/margin/etc.) and the row/column buttons
        // act on. Click any block in the visual editor to select it.
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

        // Click delegation: figure out which top-level child of the grid
        // editor was clicked (even if the click landed on a nested cell or
        // text node inside it) and make that the active element.
        gridEditor.addEventListener('click', function (e) {
            let node = e.target;

            while (node && node.parentElement !== gridEditor) {
                node = node.parentElement;
            }

            if (node) {
                selectElement(node);
            }
        });

        // Returns the block the Style panel / row-column buttons should
        // act on: the one the user selected, falling back to the last
        // block in the editor if nothing is selected yet (or the
        // selection was removed).
        function currentElement() {
            if (activeElement && activeElement.parentElement === gridEditor) {
                return activeElement;
            }

            return gridEditor.lastElementChild;
        }

        // Kept for any old callers/readability — table-specific code
        // paths still ask for this explicitly.
        function currentTable() {
            const el = currentElement();
            return el && el.tagName === 'TABLE' ? el : null;
        }

        function currentDiv() {
            const el = currentElement();
            return el && el.tagName !== 'TABLE' ? el : null;
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

        // Applies the Style panel to either a <table> (per-cell, like before)
        // or a plain <div>/box (directly on the element itself), so padding,
        // margin, border, font size and alignment all work the same way
        // no matter which element type is currently loaded.
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

            // Generic element (div, p, section, etc.)
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

        // Backwards-compatible alias — same behaviour, new name.
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

            // A plain div/box is made directly editable.
            el.setAttribute('contenteditable', 'true');
            el.addEventListener('input', syncTextareaFromGrid);
            el.addEventListener('blur', syncTextareaFromGrid);
        }

        function syncTextareaFromGrid() {
            const blocks = Array.from(gridEditor.children);

            if (!blocks.length) {
                return;
            }

            // Serialize EVERY block currently in the editor (not just one),
            // so a <div> and a <table> living side by side both survive
            // every keystroke instead of the second one wiping the first.
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

            // Force sane, consistent defaults so nothing looks patchy
            // and nothing gets clipped by the card's overflow:hidden.
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

            // Auto height so long content is never cropped by the card edge.
            if (tableHeightInput) {
                tableHeightInput.value = 0;
                tableHeightInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }

        function loadFromTextarea() {
            const raw = tableDataTextarea.value || '';
            const temp = document.createElement('div');

            temp.innerHTML = raw;

            // Load EVERY top-level element in the box — a <div> AND a
            // <table> together, several divs, etc. — not just the first
            // one. This is what was silently dropping your <div>: only
            // one block was ever kept, and every edit re-saved just that
            // one, erasing the rest.
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

            // Default the Style panel / row-column buttons to the table if
            // there is one (matches old behaviour), otherwise the last
            // block loaded. Click any block to select a different one.
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

                if (!table) {
                    return;
                }

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

                if (!table || table.rows.length <= 1) {
                    return;
                }

                table.deleteRow(table.rows.length - 1);
                syncTextareaFromGrid();
            });
        }

        if (addColBtn) {
            addColBtn.addEventListener('click', function () {
                const table = currentTable();

                if (!table) {
                    return;
                }

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

                if (!table || !table.rows.length || table.rows[0].cells.length <= 1) {
                    return;
                }

                Array.from(table.rows).forEach(function (row) {
                    row.deleteCell(row.cells.length - 1);
                });

                syncTextareaFromGrid();
            });
        }

        [
            borderWidthInput,
            borderColorInput,
            paddingTopInput,
            paddingRightInput,
            paddingBottomInput,
            paddingLeftInput,
            marginTopInput,
            marginRightInput,
            marginBottomInput,
            marginLeftInput,
            fontSizeInput,
            textAlignInput,
            headerToggleInput
        ].forEach(function (input) {
            if (!input) {
                return;
            }

            input.addEventListener('input', function () {
                const el = currentElement();

                if (!el) {
                    return;
                }

                applyStylesToElement(el);
                syncTextareaFromGrid();
            });

            input.addEventListener('change', function () {
                const el = currentElement();

                if (!el) {
                    return;
                }

                applyStylesToElement(el);
                syncTextareaFromGrid();
            });
        });

        // Load whatever is already saved so the visual editor
        // isn't empty on page load if a table or div already exists.
        if ((tableDataTextarea.value || '').trim()) {
            loadFromTextarea();
        }

    })();


    // =========================================================
    // BACKGROUND AREAS / SHAPES
    // =========================================================

    const backgroundAreasContainer =
        document.getElementById('backgroundAreasContainer');

    const addBackgroundAreaBtn =
        document.getElementById('addBackgroundAreaBtn');

    let backgroundAreas = [];


    function generateBackgroundAreaId() {

        return 'backgroundArea_' +
            Date.now() +
            '_' +
            Math.floor(Math.random() * 100000);
    }


    function createBackgroundAreaElement(area) {

        const el =
            document.createElement('div');

        el.id = area.id;

        el.className =
            'background-area';

        el.dataset.backgroundArea =
            'true';

        el.style.left =
            (parseFloat(area.x) || 0) + 'px';

        el.style.top =
            (parseFloat(area.y) || 0) + 'px';

        el.style.width =
            (parseFloat(area.width) || 100) + 'px';

        el.style.height =
            (parseFloat(area.height) || 50) + 'px';

        el.style.backgroundColor =
            area.backgroundColor || '#9e1b32';

        el.style.opacity =
            area.opacity !== undefined
                ? area.opacity
                : 1;

        el.style.borderRadius =
            (parseFloat(area.borderRadius) || 0) + 'px';

        if (area.visible === false) {
            el.style.display = 'none';
        }

        // Put the shape behind normal .el fields.
        const firstField =
            card.querySelector('.el');

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

        const wrapper =
            document.createElement('div');

        wrapper.className =
            'background-area-control';

        wrapper.dataset.areaId =
            area.id;

        wrapper.style.cssText = `
            border:1px solid #e1e3e7;
            border-radius:7px;
            padding:10px;
            background:#fafbfc;
            margin-bottom:8px;
        `;


        const header =
            document.createElement('div');

        header.style.cssText = `
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:8px;
        `;


        const title =
            document.createElement('strong');

        title.textContent =
            area.name ||
            'Background Area';

        title.style.cssText = `
            font-size:12px;
            color:var(--maroon-dark);
        `;


        const visibilityLabel =
            document.createElement('label');

        visibilityLabel.className =
            'switch';

        visibilityLabel.innerHTML = `
            <input
                type="checkbox"
                class="background-area-visible"
                ${area.visible !== false ? 'checked' : ''}
            >
            <span class="slider"></span>
        `;


        header.appendChild(title);
        header.appendChild(visibilityLabel);

        wrapper.appendChild(header);


        const positionRow =
            document.createElement('div');

        positionRow.className =
            'row4';

        positionRow.innerHTML = `

            <div class="field">
                <label>X</label>
                <input
                    type="number"
                    class="background-area-x"
                    value="${parseFloat(area.x) || 0}"
                >
            </div>

            <div class="field">
                <label>Y</label>
                <input
                    type="number"
                    class="background-area-y"
                    value="${parseFloat(area.y) || 0}"
                >
            </div>

            <div class="field">
                <label>W</label>
                <input
                    type="number"
                    min="1"
                    class="background-area-width"
                    value="${parseFloat(area.width) || 100}"
                >
            </div>

            <div class="field">
                <label>H</label>
                <input
                    type="number"
                    min="1"
                    class="background-area-height"
                    value="${parseFloat(area.height) || 50}"
                >
            </div>

        `;

        wrapper.appendChild(positionRow);


        const styleRow =
            document.createElement('div');

        styleRow.className =
            'row2';

        styleRow.innerHTML = `

            <div class="field">
                <label>Color</label>
                <input
                    type="color"
                    class="background-area-color"
                    value="${area.backgroundColor || '#9e1b32'}"
                >
            </div>

            <div class="field">
                <label>Opacity</label>
                <input
                    type="number"
                    class="background-area-opacity"
                    min="0"
                    max="1"
                    step="0.05"
                    value="${
                        area.opacity !== undefined
                            ? area.opacity
                            : 1
                    }"
                >
            </div>

        `;

        wrapper.appendChild(styleRow);


        const radiusRow =
            document.createElement('div');

        radiusRow.className =
            'row2';

        radiusRow.innerHTML = `

            <div class="field">
                <label>Radius</label>
                <input
                    type="number"
                    class="background-area-radius"
                    min="0"
                    value="${parseFloat(area.borderRadius) || 0}"
                >
            </div>

            <div
                style="
                    display:flex;
                    align-items:flex-end;
                    justify-content:flex-end;
                "
            >
                <button
                    type="button"
                    class="background-area-delete"
                    style="
                        background:#dc3545;
                        color:#fff;
                        border:none;
                        border-radius:5px;
                        padding:7px 10px;
                        cursor:pointer;
                        font-size:11px;
                    "
                >Delete</button>
            </div>

        `;

        wrapper.appendChild(radiusRow);

        backgroundAreasContainer.appendChild(wrapper);


        const el =
            document.getElementById(area.id);

        if (!el) {
            return;
        }


        const xInput =
            wrapper.querySelector(
                '.background-area-x'
            );

        const yInput =
            wrapper.querySelector(
                '.background-area-y'
            );

        const widthInput =
            wrapper.querySelector(
                '.background-area-width'
            );

        const heightInput =
            wrapper.querySelector(
                '.background-area-height'
            );

        const colorInput =
            wrapper.querySelector(
                '.background-area-color'
            );

        const opacityInput =
            wrapper.querySelector(
                '.background-area-opacity'
            );

        const radiusInput =
            wrapper.querySelector(
                '.background-area-radius'
            );

        const visibleInput =
            wrapper.querySelector(
                '.background-area-visible'
            );


        function updateArea() {

            const x =
                parseFloat(xInput.value) || 0;

            const y =
                parseFloat(yInput.value) || 0;

            const width =
                Math.max(
                    1,
                    parseFloat(widthInput.value) || 1
                );

            const height =
                Math.max(
                    1,
                    parseFloat(heightInput.value) || 1
                );

            const color =
                colorInput.value || '#9e1b32';

            const opacityValue =
                parseFloat(opacityInput.value);

            const opacity =
                Number.isFinite(opacityValue)
                    ? Math.max(
                        0,
                        Math.min(1, opacityValue)
                    )
                    : 1;

            const radius =
                Math.max(
                    0,
                    parseFloat(radiusInput.value) || 0
                );


            el.style.left =
                x + 'px';

            el.style.top =
                y + 'px';

            el.style.width =
                width + 'px';

            el.style.height =
                height + 'px';

            el.style.backgroundColor =
                color;

            el.style.opacity =
                opacity;

            el.style.borderRadius =
                radius + 'px';

            el.style.display =
                visibleInput.checked
                    ? ''
                    : 'none';


            area.x = x;
            area.y = y;
            area.width = width;
            area.height = height;
            area.backgroundColor = color;
            area.opacity = opacity;
            area.borderRadius = radius;
            area.visible = visibleInput.checked;
        }


        [
            xInput,
            yInput,
            widthInput,
            heightInput,
            colorInput,
            opacityInput,
            radiusInput
        ].forEach(function(input) {

            input.addEventListener(
                'input',
                updateArea
            );

        });


        visibleInput.addEventListener(
            'change',
            updateArea
        );


        const deleteBtn =
            wrapper.querySelector(
                '.background-area-delete'
            );

        deleteBtn.addEventListener(
            'click',
            function(e) {

                e.stopPropagation();

                if (!confirm(
                    'Delete this background area?'
                )) {
                    return;
                }

                el.remove();

                wrapper.remove();

                backgroundAreas =
                    backgroundAreas.filter(
                        function(item) {
                            return item.id !== area.id;
                        }
                    );

                updateBackgroundAreasEmptyState();
            }
        );


        updateArea();
    }


    function updateBackgroundAreasEmptyState() {

        const empty =
            document.getElementById(
                'backgroundAreasEmpty'
            );

        if (!empty) {
            return;
        }

        empty.style.display =
            backgroundAreas.length
                ? 'none'
                : 'block';
    }


    function addBackgroundArea() {

        const number =
            backgroundAreas.length + 1;

        const area = {

            id:
                generateBackgroundAreaId(),

            name:
                'Background Area ' + number,

            x: 20,

            y: 20,

            width: 100,

            height: 50,

            backgroundColor:
                '#9e1b32',

            opacity:
                1,

            borderRadius:
                0,

            visible:
                true
        };


        backgroundAreas.push(area);

        createBackgroundAreaElement(area);

        createBackgroundAreaControl(area);

        updateBackgroundAreasEmptyState();
    }


    if (addBackgroundAreaBtn) {

        addBackgroundAreaBtn.addEventListener(
            'click',
            function(e) {

                // Do not collapse the group.
                e.stopPropagation();

                addBackgroundArea();
            }
        );

    }


    function loadBackgroundAreas() {

        if (
            !initialLayout ||
            !initialLayout.fields
        ) {
            updateBackgroundAreasEmptyState();
            return;
        }


        Object.keys(
            initialLayout.fields
        ).forEach(function(key) {

            const item =
                initialLayout.fields[key];

            if (
                !item ||
                item.type !== 'shape'
            ) {
                return;
            }


            const area = {

                id:
                    item.id || key,

                name:
                    item.name ||
                    'Background Area',

                x:
                    parseFloat(item.x) || 0,

                y:
                    parseFloat(item.y) || 0,

                width:
                    parseFloat(item.width) || 100,

                height:
                    parseFloat(item.height) || 50,

                backgroundColor:
                    item.backgroundColor ||
                    '#9e1b32',

                opacity:
                    item.opacity !== undefined
                        ? parseFloat(item.opacity)
                        : 1,

                borderRadius:
                    parseFloat(
                        item.borderRadius
                    ) || 0,

                visible:
                    item.visible !== false
            };


            backgroundAreas.push(area);

            createBackgroundAreaElement(area);

            createBackgroundAreaControl(area);

        });


        updateBackgroundAreasEmptyState();
    }


    loadBackgroundAreas();


    // =========================================================
    // EXIF ORIENTATION
    // =========================================================

    function readExifOrientation(arrayBuffer) {

        const view = new DataView(arrayBuffer);

        if (
            view.byteLength < 4 ||
            view.getUint16(0, false) !== 0xFFD8
        ) {
            return 1;
        }

        let offset = 2;

        while (offset < view.byteLength - 1) {

            const marker = view.getUint16(offset, false);

            offset += 2;

            if (marker === 0xFFE1) {

                if (
                    view.getUint32(offset + 2, false) !== 0x45786966
                ) {
                    return 1;
                }

                const tiffOffset = offset + 8;

                const little =
                    view.getUint16(tiffOffset, false) === 0x4949;

                const firstIFDOffset =
                    view.getUint32(tiffOffset + 4, little);

                const dirStart =
                    tiffOffset + firstIFDOffset;

                const entries =
                    view.getUint16(dirStart, little);

                for (let i = 0; i < entries; i++) {

                    const entryOffset =
                        dirStart + 2 + i * 12;

                    if (
                        view.getUint16(entryOffset, little) === 0x0112
                    ) {

                        return view.getUint16(
                            entryOffset + 8,
                            little
                        );
                    }
                }

                return 1;

            } else if (
                (marker & 0xFF00) !== 0xFF00
            ) {

                break;

            } else {

                offset += view.getUint16(offset, false);
            }
        }

        return 1;
    }


    // =========================================================
    // NORMALIZE IMAGE
    // =========================================================

    function normalizeImage(file, callback) {

        const reader = new FileReader();

        reader.onload = function (e) {

            const arrayBuffer = e.target.result;

            let orientation = 1;

            try {

                orientation =
                    readExifOrientation(arrayBuffer);

            } catch (error) {

                orientation = 1;
            }

            const url =
                URL.createObjectURL(
                    new Blob([arrayBuffer])
                );

            const img = new Image();

            img.onload = function () {

                const w = img.naturalWidth;
                const h = img.naturalHeight;

                const canvas =
                    document.createElement('canvas');

                const rotated =
                    orientation >= 5 &&
                    orientation <= 8;

                canvas.width =
                    rotated ? h : w;

                canvas.height =
                    rotated ? w : h;

                const ctx =
                    canvas.getContext('2d');

                switch (orientation) {

                    case 2:
                        ctx.transform(
                            -1, 0, 0, 1, w, 0
                        );
                        break;

                    case 3:
                        ctx.transform(
                            -1, 0, 0, -1, w, h
                        );
                        break;

                    case 4:
                        ctx.transform(
                            1, 0, 0, -1, 0, h
                        );
                        break;

                    case 5:
                        ctx.transform(
                            0, 1, 1, 0, 0, 0
                        );
                        break;

                    case 6:
                        ctx.transform(
                            0, 1, -1, 0, h, 0
                        );
                        break;

                    case 7:
                        ctx.transform(
                            0, -1, -1, 0, h, w
                        );
                        break;

                    case 8:
                        ctx.transform(
                            0, -1, 1, 0, 0, w
                        );
                        break;
                }

                ctx.drawImage(img, 0, 0);

                URL.revokeObjectURL(url);

                callback(
                    canvas.toDataURL('image/jpeg', 0.92),
                    canvas.width,
                    canvas.height
                );
            };

            img.src = url;
        };

        reader.readAsArrayBuffer(file);
    }


    // =========================================================
    // BACKGROUND UPLOAD
    // =========================================================

    const bgUpload =
        document.getElementById('bgUpload');

    if (bgUpload) {

        bgUpload.addEventListener(
            'change',
            function (e) {

                const file =
                    e.target.files[0];

                if (!file) {
                    return;
                }

                normalizeImage(
                    file,
                    function (dataUrl, w, h) {

                        card.style.backgroundImage =
                            'url("' + dataUrl + '")';
                    }
                );
            }
        );
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

        return label
            .split(' ')
            .map(function (word) {
                if (!word) {
                    return '';
                }
                return word.charAt(0).toUpperCase() + word.slice(1);
            })
            .join(' ');
    }


    const fields = [

        {
            key: 'logo',
            el: 'elLogo',
            x: 'logoX',
            y: 'logoY',
            w: 'logoW',
            h: 'logoH',
            toggle: 'logoToggle'
        },

        {
            key: 'schoolName',
            el: 'elSchoolName',
            x: 'schoolNameX',
            y: 'schoolNameY',
            text: 'schoolNameText',
            size: 'schoolNameSize',
            color: 'schoolNameColor',
            weight: 'schoolNameWeight',
            toggle: 'schoolNameToggle'
        },

        {
            key: 'address',
            el: 'elAddress',
            x: 'addressX',
            y: 'addressY',
            text: 'addressText',
            size: 'addressSize',
            color: 'addressColor',
            weight: 'addressWeight',
            toggle: 'addressToggle'
        },

        {
            key: 'studentAddress',
            el: 'elStudentAddress',
            x: 'studentAddressX',
            y: 'studentAddressY',
            text: 'studentAddressText',
            size: 'studentAddressSize',
            color: 'studentAddressColor',
            weight: 'studentAddressWeight',
            toggle: 'studentAddressToggle'
        },

        {
            key: 'session',
            el: 'elSession',
            x: 'sessionX',
            y: 'sessionY',
            text: 'sessionText',
            size: 'sessionSize',
            color: 'sessionColor',
            weight: 'sessionWeight',
            toggle: 'sessionToggle'
        },

        {
            key: 'photo',
            el: 'elPhoto',
            x: 'photoX',
            y: 'photoY',
            w: 'photoW',
            h: 'photoH',
            radius: 'photoRadius',
            toggle: 'photoToggle'
        },

        {
            key: 'name',
            el: 'elName',
            x: 'nameX',
            y: 'nameY',
            text: 'nameText',
            size: 'nameSize',
            color: 'nameColor',
            weight: 'nameWeight',
            toggle: 'nameToggle'
        },

        {
            key: 'father',
            el: 'elFather',
            x: 'fatherX',
            y: 'fatherY',
            text: 'fatherText',
            size: 'fatherSize',
            color: 'fatherColor',
            weight: 'fatherWeight',
            toggle: 'fatherToggle'
        },

        {
            key: 'mother',
            el: 'elMother',
            x: 'motherX',
            y: 'motherY',
            text: 'motherText',
            size: 'motherSize',
            color: 'motherColor',
            weight: 'motherWeight',
            toggle: 'motherToggle'
        },

        {
            key: 'class',
            el: 'elClass',
            x: 'classX',
            y: 'classY',
            text: 'classText',
            size: 'classSize',
            color: 'classColor',
            weight: 'classWeight',
            toggle: 'classToggle'
        },

        {
            key: 'dob',
            el: 'elDob',
            x: 'dobX',
            y: 'dobY',
            text: 'dobText',
            size: 'dobSize',
            color: 'dobColor',
            weight: 'dobWeight',
            toggle: 'dobToggle'
        },

        {
            key: 'adm',
            el: 'elAdm',
            x: 'admX',
            y: 'admY',
            text: 'admText',
            size: 'admSize',
            color: 'admColor',
            weight: 'admWeight',
            toggle: 'admToggle'
        },

        {
            key: 'blood',
            el: 'elBlood',
            x: 'bloodX',
            y: 'bloodY',
            text: 'bloodText',
            size: 'bloodSize',
            color: 'bloodColor',
            weight: 'bloodWeight',
            toggle: 'bloodToggle'
        },

        {
            key: 'sign',
            el: 'elSign',
            x: 'signX',
            y: 'signY',
            w: 'signW',
            h: 'signH',
            color: 'signColor',
            weight: 'signWeight',
            toggle: 'signToggle'
        },

        {
            key: 'qr',
            el: 'elQr',
            x: 'qrX',
            y: 'qrY',
            w: 'qrSize',
            h: 'qrSize',
            toggle: 'qrToggle'
        }

    ];


    // =========================================================
    // APPLY FIELD
    // =========================================================

    function applyField(f) {

        const el =
            document.getElementById(f.el);

        if (!el) {
            return;
        }


        // X
        if (f.x) {

            const input =
                document.getElementById(f.x);

            if (input) {

                el.style.left =
                    (input.value || 0) + 'px';
            }
        }


        // Y
        if (f.y) {

            const input =
                document.getElementById(f.y);

            if (input) {

                el.style.top =
                    (input.value || 0) + 'px';
            }
        }


        // WIDTH
        if (f.w) {

            const input =
                document.getElementById(f.w);

            if (input) {

                el.style.width =
                    (input.value || 0) + 'px';
            }
        }


        // HEIGHT
        if (f.h) {

            const input =
                document.getElementById(f.h);

            if (input) {

                el.style.height =
                    (input.value || 0) + 'px';
            }
        }


        // BORDER RADIUS
        if (f.radius) {

            const input =
                document.getElementById(f.radius);

            if (input) {

                el.style.borderRadius =
                    (input.value || 0) + 'px';
            }
        }


        // TEXT
        if (f.text) {

            const input =
                document.getElementById(f.text);

            if (input) {

                el.textContent =
                    input.value;
            }
        }


        // FONT SIZE
        if (f.size) {

            const input =
                document.getElementById(f.size);

            if (input) {

                el.style.fontSize =
                    (input.value || 12) + 'px';
            }
        }


        // COLOR
        if (f.color) {

            const input =
                document.getElementById(f.color);

            if (input) {

                el.style.color =
                    input.value;
            }
        }


        // FONT WEIGHT
        if (f.weight) {

            const input =
                document.getElementById(f.weight);

            if (input) {

                el.style.fontWeight =
                    input.value;
            }
        }


        // VISIBILITY
        if (f.toggle) {

            const toggle =
                document.getElementById(f.toggle);

            if (toggle) {

                const visible =
                    toggle.checked;

                el.style.display =
                    visible ? '' : 'none';

                const group =
                    toggle.closest('.group');

                if (group) {

                    group.classList.toggle(
                        'field-off',
                        !visible
                    );
                }
            }
        }

        // apply any custom CSS saved in a <style id="css_<elId>"> tag
        try {
            const styleTag = document.getElementById('css_' + f.el);
            if (styleTag && styleTag.textContent) {
                // already applied via style tag
            }
        } catch (err) {
            // ignore
        }
    }


    // Helper to apply custom CSS for an element using a <style> tag
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

        [
            'x',
            'y',
            'w',
            'h',
            'radius',
            'text',
            'size',
            'color',
            'weight',
            'toggle'

        ].forEach(function (key) {

            if (!f[key]) {
                return;
            }

            const input =
                document.getElementById(f[key]);

            if (!input) {
                return;
            }

            const event =
                key === 'toggle'
                    ? 'change'
                    : 'input';

            input.addEventListener(
                event,
                function () {

                    applyField(f);
                }
            );
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
                item.classList.toggle('active', item === button);
            });

            document.querySelectorAll('.layout-tab-panel').forEach(function (panel) {
                const isActive = panel.id === tab;
                panel.classList.toggle('active', isActive);
                panel.style.display = isActive ? 'block' : 'none';
            });

            const inlinePanel = document.getElementById('inline-tab');
            const inlineTextarea = document.getElementById('usevalTextarea');
            if (inlineTextarea) {
                inlineTextarea.disabled = tab !== 'inline-tab';
            }
            if (inlinePanel) {
                inlinePanel.style.display = tab === 'inline-tab' ? 'block' : 'none';
            }
        });
    });

    // Create small CSS textarea under each group and wire it
    fields.forEach(function (f) {
        const refId = f.x || f.text || f.w || f.h || f.size || f.toggle;
        if (!refId) return;
        const ref = document.getElementById(refId);
        if (!ref) return;
        const group = ref.closest('.group');
        if (!group) return;

        // Avoid duplicate
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
        ta.placeholder = 'Custom CSS for the element (eg. transform: rotate(3deg);)';
        ta.rows = 2;

        // Prefill from initial layout if available
        try {
            if (initialLayout && initialLayout.fields && initialLayout.fields[f.key] && initialLayout.fields[f.key].css) {
                ta.value = initialLayout.fields[f.key].css;
            }
        } catch (err) {
            // ignore
        }

        ta.addEventListener('input', function () {
            applyCustomCss(f.el, ta.value);
        });

        wrapper.appendChild(label);
        wrapper.appendChild(ta);

        const body = group.querySelector('.group-body');
        if (body) body.appendChild(wrapper);

        // apply immediately
        applyCustomCss(f.el, ta.value);
    });


    // =========================================================
    // SHOW ALL
    // =========================================================

    const showAllBtn =
        document.getElementById('showAllBtn');

    if (showAllBtn) {

        showAllBtn.addEventListener(
            'click',
            function () {

                fields.forEach(function (f) {

                    if (!f.toggle) {
                        return;
                    }

                    const toggle =
                        document.getElementById(
                            f.toggle
                        );

                    toggle.checked = true;

                    applyField(f);
                });
            }
        );
    }


    // =========================================================
    // HIDE ALL
    // =========================================================

    const hideAllBtn =
        document.getElementById('hideAllBtn');

    if (hideAllBtn) {

        hideAllBtn.addEventListener(
            'click',
            function () {

                fields.forEach(function (f) {

                    if (!f.toggle) {
                        return;
                    }

                    const toggle =
                        document.getElementById(
                            f.toggle
                        );

                    toggle.checked = false;

                    applyField(f);
                });
            }
        );
    }


    // =========================================================
    // DEFAULT FONT SETTINGS
    // =========================================================

    const nameElement =
        document.getElementById('elName');

    if (nameElement) {

        nameElement.style.fontWeight = '700';

        nameElement.style.textTransform =
            'uppercase';
    }


    const schoolNameElement =
        document.getElementById(
            'elSchoolName'
        );

    if (schoolNameElement) {

        schoolNameElement.style.fontWeight =
            '700';
    }


    // =========================================================
    // QR CODE
    // =========================================================

    const qrDataInput =
        document.getElementById('qrData');

    function updateQr() {

        if (!qrDataInput) {
            return;
        }

        const value =
            encodeURIComponent(
                qrDataInput.value || ''
            );

        const qr =
            document.getElementById('elQr');

        if (qr) {

            qr.src =
                'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data='
                + value;
        }
    }


    if (qrDataInput) {

        qrDataInput.addEventListener(
            'input',
            updateQr
        );

        updateQr();
    }


    // =========================================================
    // PHOTO UPLOAD
    // =========================================================

    const photoUpload =
        document.getElementById('photoUpload');

    if (photoUpload) {

        photoUpload.addEventListener(
            'change',
            function (e) {

                const file =
                    e.target.files[0];

                if (!file) {
                    return;
                }

                normalizeImage(
                    file,
                    function (dataUrl) {

                        document.getElementById(
                            'elPhoto'
                        ).src = dataUrl;
                    }
                );
            }
        );
    }


    // =========================================================
    // LOGO UPLOAD
    // =========================================================

    const logoUpload =
        document.getElementById('logoUpload');

    if (logoUpload) {

        logoUpload.addEventListener(
            'change',
            function (e) {

                const file =
                    e.target.files[0];

                if (!file) {
                    return;
                }

                normalizeImage(
                    file,
                    function (dataUrl) {

                        document.getElementById(
                            'elLogo'
                        ).src = dataUrl;
                    }
                );
            }
        );
    }


    // =========================================================
    // SIGNATURE UPLOAD
    // =========================================================

    const signUpload =
        document.getElementById('signUpload');

    if (signUpload) {

        signUpload.addEventListener(
            'change',
            function (e) {

                const file =
                    e.target.files[0];

                if (!file) {
                    return;
                }

                normalizeImage(
                    file,
                    function (dataUrl) {

                        document.getElementById(
                            'elSign'
                        ).src = dataUrl;
                    }
                );
            }
        );
    }


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

        el.addEventListener(
            'mousedown',
            function (e) {

                dragEl = el;

                el.classList.add(
                    'dragging'
                );

                const rect =
                    card.getBoundingClientRect();

                const scale =
                    rect.width / CARD_W;

                offX =
                    (
                        e.clientX -
                        rect.left
                    ) / scale -
                    parseFloat(
                        el.style.left || 0
                    );

                offY =
                    (
                        e.clientY -
                        rect.top
                    ) / scale -
                    parseFloat(
                        el.style.top || 0
                    );

                e.preventDefault();
            }
        );
    }


    document
        .querySelectorAll('.el, .background-area')
        .forEach(attachDrag);


    document.addEventListener(
        'mousemove',
        function (e) {

            if (!dragEl) {
                return;
            }

            const rect =
                card.getBoundingClientRect();

            const scale =
                rect.width / CARD_W;

            let nx =
                Math.round(
                    (
                        e.clientX -
                        rect.left
                    ) / scale -
                    offX
                );

            let ny =
                Math.round(
                    (
                        e.clientY -
                        rect.top
                    ) / scale -
                    offY
                );

            nx =
                Math.max(
                    0,
                    Math.min(CARD_W, nx)
                );

            ny =
                Math.max(
                    0,
                    Math.min(CARD_H, ny)
                );

            dragEl.style.left =
                nx + 'px';

            dragEl.style.top =
                ny + 'px';


            const f =
                xyControlsFor(
                    dragEl.id
                );

            if (f) {

                if (f.x) {

                    document.getElementById(
                        f.x
                    ).value = nx;
                }

                if (f.y) {

                    document.getElementById(
                        f.y
                    ).value = ny;
                }
            }


            // Keep Background Area X/Y controls
            // synchronized while dragging.
            if (
                dragEl.classList.contains(
                    'background-area'
                )
            ) {

                const area =
                    backgroundAreas.find(
                        function(item) {
                            return item.id === dragEl.id;
                        }
                    );

                if (area) {

                    area.x = nx;
                    area.y = ny;

                    const panel =
                        document.querySelector(
                            '.background-area-control[data-area-id="' +
                            area.id +
                            '"]'
                        );

                    if (panel) {

                        const xInput =
                            panel.querySelector(
                                '.background-area-x'
                            );

                        const yInput =
                            panel.querySelector(
                                '.background-area-y'
                            );

                        if (xInput) {
                            xInput.value = nx;
                        }

                        if (yInput) {
                            yInput.value = ny;
                        }
                    }
                }
            }

            // Keep clone control panels synced while dragging
            const cloneMatch =
                fieldClones.find(function (c) {
                    return c.id === dragEl.id;
                });

            if (cloneMatch) {

                const panel =
                    document.querySelector(
                        '.field-clone-control[data-clone-id="' +
                        cloneMatch.id +
                        '"]'
                    );

                if (panel) {

                    const xI =
                        panel.querySelector('.clone-x');

                    const yI =
                        panel.querySelector('.clone-y');

                    if (xI) xI.value = nx;
                    if (yI) yI.value = ny;
                }
            }
        }
    );


    document.addEventListener(
        'mouseup',
        function () {

            if (dragEl) {

                dragEl.classList.remove(
                    'dragging'
                );
            }

            dragEl = null;
        }
    );


    // =========================================================
    // DUPLICATE / CLONE FIELDS
    // (logo, name, photo, etc. — unlimited copies of any field)
    // =========================================================

    let fieldClones = []; // { id, baseKey, isImage }

    function generateCloneId(baseKey) {

        return baseKey +
            '_copy_' +
            Date.now() +
            '_' +
            Math.floor(Math.random() * 10000);
    }

    function rgbToHex(rgb) {

        if (!rgb) {
            return '#1f2430';
        }

        if (rgb.startsWith('#')) {
            return rgb;
        }

        const m = rgb.match(/\d+/g);

        if (!m) {
            return '#1f2430';
        }

        return '#' + m.slice(0, 3).map(function (x) {

            const h = parseInt(x, 10).toString(16);

            return h.length === 1 ? '0' + h : h;

        }).join('');
    }

    function addDuplicateButtons() {

        fields.forEach(function (f) {

            const anchorId =
                f.toggle || f.x || f.text;

            const anchorInput =
                document.getElementById(anchorId);

            if (!anchorInput) {
                return;
            }

            const grp =
                anchorInput.closest('.group');

            if (!grp) {
                return;
            }

            const titleRight =
                grp.querySelector('.group-title-right');

            if (!titleRight || titleRight.querySelector('.dup-btn')) {
                return;
            }

            const btn =
                document.createElement('button');

            btn.type = 'button';
            btn.className = 'dup-btn';
            btn.title = 'Duplicate this field';
            btn.textContent = '⎘';

            btn.style.cssText =
                'background:none;border:none;cursor:pointer;' +
                'font-size:14px;color:var(--accent);padding:0 2px;';

            btn.addEventListener('click', function (e) {

                e.stopPropagation();

                addFieldClone(f.key);
            });

            titleRight.insertBefore(
                btn,
                titleRight.firstChild
            );
        });
    }

    function addFieldClone(baseKey, preset) {

        const f =
            fields.find(function (x) {
                return x.key === baseKey;
            });

        if (!f) {
            return;
        }

        const baseEl =
            document.getElementById(f.el);

        if (!baseEl) {
            return;
        }

        const cloneId =
            (preset && preset.id) ||
            generateCloneId(baseKey);

        const isImage = !f.text;

        let el;

        if (isImage) {

            el = document.createElement('img');

            el.src =
                (preset && preset.src) ||
                baseEl.src;

            el.className =
                baseEl.className;

        } else {

            el = document.createElement('div');

            el.className =
                baseEl.className;

            el.textContent =
                (preset && preset.text) ||
                baseEl.textContent;
        }

        el.id = cloneId;

        el.style.position = 'absolute';

        el.style.left =
            (
                (preset && preset.x !== undefined)
                    ? preset.x
                    : (parseFloat(baseEl.style.left) || 0) + 15
            ) + 'px';

        el.style.top =
            (
                (preset && preset.y !== undefined)
                    ? preset.y
                    : (parseFloat(baseEl.style.top) || 0) + 15
            ) + 'px';

        if (isImage) {

            el.style.width =
                (
                    (preset && preset.width) ||
                    parseFloat(baseEl.style.width) ||
                    80
                ) + 'px';

            el.style.height =
                (
                    (preset && preset.height) ||
                    parseFloat(baseEl.style.height) ||
                    80
                ) + 'px';

        } else {

            el.style.fontSize =
                (
                    (preset && preset.fontSize) ||
                    parseFloat(baseEl.style.fontSize) ||
                    14
                ) + 'px';

            el.style.color =
                (preset && preset.color) ||
                baseEl.style.color ||
                '#1f2430';

            el.style.fontWeight =
                (preset && preset.fontWeight) ||
                baseEl.style.fontWeight ||
                '400';
        }

        if (preset && preset.visible === false) {
            el.style.display = 'none';
        }

        card.appendChild(el);

        attachDrag(el);

        const cloneMeta = {
            id: cloneId,
            baseKey: baseKey,
            isImage: isImage
        };

        fieldClones.push(cloneMeta);

        createCloneControl(f, cloneMeta, el);
    }

    function createCloneControl(f, cloneMeta, el) {

        const anchorId =
            f.toggle || f.x || f.text;

        const anchorInput =
            document.getElementById(anchorId);

        const grp =
            anchorInput ? anchorInput.closest('.group') : null;

        const body =
            grp ? grp.querySelector('.group-body') : null;

        if (!body) {
            return;
        }

        const wrapper =
            document.createElement('div');

        wrapper.className = 'field-clone-control';
        wrapper.dataset.cloneId = cloneMeta.id;

        wrapper.style.cssText =
            'border:1px dashed #d5d8dd;border-radius:6px;' +
            'padding:8px;margin-top:6px;background:#fbfbfc;';

        const header =
            document.createElement('div');

        header.style.cssText =
            'display:flex;align-items:center;' +
            'justify-content:space-between;margin-bottom:6px;';

        header.innerHTML =
            '<strong style="font-size:11px;color:var(--maroon-dark);">' +
            'Copy of ' + f.key + '</strong>';

        const delBtn =
            document.createElement('button');

        delBtn.type = 'button';
        delBtn.textContent = 'Delete';

        delBtn.style.cssText =
            'background:#dc3545;color:#fff;border:none;' +
            'border-radius:4px;padding:3px 7px;font-size:10px;' +
            'cursor:pointer;';

        delBtn.addEventListener('click', function (e) {

            e.stopPropagation();

            el.remove();
            wrapper.remove();

            fieldClones =
                fieldClones.filter(function (c) {
                    return c.id !== cloneMeta.id;
                });
        });

        header.appendChild(delBtn);
        wrapper.appendChild(header);

        if (!cloneMeta.isImage) {

            const textField =
                document.createElement('div');

            textField.className = 'field';

            textField.innerHTML =
                '<label>Text</label>' +
                '<input type="text" class="clone-text" value="' +
                (el.textContent || '').replace(/"/g, '&quot;') +
                '">';

            wrapper.appendChild(textField);

        } else {

            const uploadLabel =
                document.createElement('label');

            uploadLabel.className = 'filebtn';

            uploadLabel.innerHTML =
                'Click to upload image' +
                '<input type="file" class="clone-upload" accept="image/*">';

            wrapper.appendChild(uploadLabel);
        }

        const row4 =
            document.createElement('div');

        row4.className = 'row4';

        row4.innerHTML =
            '<div class="field"><label>X</label>' +
            '<input type="number" class="clone-x" value="' +
            (parseFloat(el.style.left) || 0) + '"></div>' +

            '<div class="field"><label>Y</label>' +
            '<input type="number" class="clone-y" value="' +
            (parseFloat(el.style.top) || 0) + '"></div>' +

            (
                cloneMeta.isImage
                    ?
                    '<div class="field"><label>W</label>' +
                    '<input type="number" class="clone-w" value="' +
                    (parseFloat(el.style.width) || 0) + '"></div>' +

                    '<div class="field"><label>H</label>' +
                    '<input type="number" class="clone-h" value="' +
                    (parseFloat(el.style.height) || 0) + '"></div>'
                    :
                    '<div class="field"><label>Size</label>' +
                    '<input type="number" class="clone-size" value="' +
                    (parseFloat(el.style.fontSize) || 14) + '"></div>'
            );

        wrapper.appendChild(row4);

        if (!cloneMeta.isImage) {

            const row2 =
                document.createElement('div');

            row2.className = 'row2';

            row2.innerHTML =
                '<div class="field"><label>Color</label>' +
                '<input type="color" class="clone-color" value="' +
                rgbToHex(el.style.color) + '"></div>' +

                '<div class="field"><label>Weight</label>' +
                '<select class="clone-weight">' +
                '<option value="700"' +
                (el.style.fontWeight === '700' ? ' selected' : '') +
                '>Bold</option>' +
                '<option value="400"' +
                (el.style.fontWeight !== '700' ? ' selected' : '') +
                '>Normal</option></select></div>';

            wrapper.appendChild(row2);
        }

        body.appendChild(wrapper);

        const xInput =
            wrapper.querySelector('.clone-x');

        const yInput =
            wrapper.querySelector('.clone-y');

        xInput.addEventListener('input', function () {
            el.style.left = (parseFloat(xInput.value) || 0) + 'px';
        });

        yInput.addEventListener('input', function () {
            el.style.top = (parseFloat(yInput.value) || 0) + 'px';
        });

        if (cloneMeta.isImage) {

            const wInput =
                wrapper.querySelector('.clone-w');

            const hInput =
                wrapper.querySelector('.clone-h');

            wInput.addEventListener('input', function () {
                el.style.width = (parseFloat(wInput.value) || 0) + 'px';
            });

            hInput.addEventListener('input', function () {
                el.style.height = (parseFloat(hInput.value) || 0) + 'px';
            });

            const uploadInput =
                wrapper.querySelector('.clone-upload');

            uploadInput.addEventListener('change', function (e) {

                const file = e.target.files[0];

                if (!file) {
                    return;
                }

                normalizeImage(file, function (dataUrl) {
                    el.src = dataUrl;
                });
            });

        } else {

            const textInput =
                wrapper.querySelector('.clone-text');

            const sizeInput =
                wrapper.querySelector('.clone-size');

            const colorInput =
                wrapper.querySelector('.clone-color');

            const weightInput =
                wrapper.querySelector('.clone-weight');

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
     


    function loadFieldClones() {

        if (!initialLayout || !initialLayout.fields) {
            return;
        }

        Object.keys(initialLayout.fields).forEach(function (key) {

            const item = initialLayout.fields[key];

            if (!item || !item.cloneOf) {
                return;
            }

            addFieldClone(item.cloneOf, {
                id: key,
                x: item.x,
                y: item.y,
                width: item.width,
                height: item.height,
                text: item.text,
                fontSize: item.fontSize,
                color: item.color,
                fontWeight: item.fontWeight,
                src: item.src,
                visible: item.visible
            });
        });
    }

    addDuplicateButtons();
    loadFieldClones();


    // =========================================================
    // COLLAPSIBLE GROUPS
    // =========================================================

    document
        .querySelectorAll('.group-title')
        .forEach(function (title) {

            title.addEventListener(
                'click',
                function () {

                    title.parentElement
                        .classList.toggle(
                            'collapsed'
                        );
                }
            );
        });


    // =========================================================
    // ZOOM
    // =========================================================

    
     
    const zoom =
        document.getElementById('zoom');

    const zoomVal =
        document.getElementById('zoomVal');

    if (zoom) {

        zoom.value = 100;
        zoomVal.textContent = '100%';
        card.style.transform = 'scale(1)';

        zoom.addEventListener(
            'input',
            function () {

                const scale =
                    zoom.value / 100;

                card.style.transform =
                    'scale(' + scale + ')';

                zoomVal.textContent =
                    zoom.value + '%';
            }
        );
    }


    // =========================================================
    // RESET
    // =========================================================

    const defaults = {};

    document
        .querySelectorAll(
            '.controls input, .controls select'
        )
        .forEach(function (input) {

            defaults[input.id] =
                input.type === 'checkbox'
                    ? input.checked
                    : input.value;
        });


    const resetBtn =
        document.getElementById('resetBtn');

    if (resetBtn) {

        resetBtn.addEventListener(
            'click',
            function () {

                Object.keys(defaults)
                    .forEach(function (id) {

                        const input =
                            document.getElementById(id);

                        if (!input) {
                            return;
                        }

                        if (
                            input.type ===
                            'checkbox'
                        ) {

                            input.checked =
                                defaults[id];

                        } else {

                            input.value =
                                defaults[id];
                        }
                    });


                fields.forEach(
                    applyField
                );

                updateQr();
            }
        );
    }


    // =========================================================
    // BUILD LAYOUT JSON
    // =========================================================
    //
    // IMPORTANT:
    // OFF TOGGLE = ELEMENT IS NOT SAVED
    //
    // =========================================================

    function buildLayoutJSON() {

        const layout = {

            cardWidth: CARD_W,

            cardHeight: CARD_H,

            background:
                card.style.backgroundImage || '',

            fields: {},

            tabledata:
                document.getElementById('tabledataTextarea')
                    ? document.getElementById('tabledataTextarea').value
                    : '',

            useval:
                document.getElementById('usevalTextarea')
                    ? document.getElementById('usevalTextarea').value
                    : '',

            tablePosition: {
                left:
                    parseFloat(
                        document.getElementById('tableLeft')?.value
                    ) || 30,

                top:
                    parseFloat(
                        document.getElementById('tableTop')?.value
                    ) || 120,

                width:
                    parseFloat(
                        document.getElementById('tableWidth')?.value
                    ) || Math.max(120, CARD_W - 60),

                height:
                    parseFloat(
                        document.getElementById('tableHeight')?.value
                    ) || 0
            }

        };


        fields.forEach(function (f) {

            // -----------------------------------------
            // CHECK TOGGLE
            // -----------------------------------------

            if (f.toggle) {

                const toggle =
                    document.getElementById(
                        f.toggle
                    );

                // OFF = DO NOT SAVE
                if (
                    !toggle ||
                    !toggle.checked
                ) {

                    return;
                }
            }


            const el =
                document.getElementById(
                    f.el
                );

            if (!el) {
                return;
            }


            const item = {

                label:
                    getFieldLabel(f.key),

                x:
                    parseFloat(
                        el.style.left
                    ) || 0,

                y:
                    parseFloat(
                        el.style.top
                    ) || 0,

                visible: true
            };


            // -----------------------------------------
            // WIDTH
            // -----------------------------------------

            if (f.w) {

                item.width =
                    parseFloat(
                        el.style.width
                    ) || 0;
            }


            // -----------------------------------------
            // HEIGHT
            // -----------------------------------------

            if (f.h) {

                item.height =
                    parseFloat(
                        el.style.height
                    ) || 0;
            }


            // -----------------------------------------
            // BORDER RADIUS
            // -----------------------------------------

            if (f.radius) {

                item.borderRadius =
                    parseFloat(
                        el.style.borderRadius
                    ) || 0;
            }


            // -----------------------------------------
            // TEXT
            // -----------------------------------------

            if (f.text) {

                const input =
                    document.getElementById(
                        f.text
                    );

                if (input) {

                    item.text =
                        input.value;
                }
            }


            // -----------------------------------------
            // FONT SIZE
            // -----------------------------------------

            if (f.size) {

                item.fontSize =
                    parseFloat(
                        el.style.fontSize
                    ) || 12;
            }


            // -----------------------------------------
            // COLOR
            // -----------------------------------------

            if (f.color) {

                item.color =
                    el.style.color || '';
            }


            // -----------------------------------------
            // FONT WEIGHT
            // -----------------------------------------

            if (f.weight) {

                item.fontWeight =
                    el.style.fontWeight || '400';
            }


            // -----------------------------------------
            // TYPE
            // -----------------------------------------

            if (f.text) {

                item.type = 'text';

            } else {

                item.type = 'image';
            }


            // If image element, include its src so server can store it
            if (item.type === 'image') {
                try {
                    item.src = el.src || '';
                } catch (err) {
                    item.src = '';
                }
            }

            // -----------------------------------------
            // CUSTOM CSS
            // -----------------------------------------

            try {
                const cssEl = document.getElementById(f.key + 'Css');
                item.css = cssEl ? cssEl.value : '';
            } catch (err) {
                item.css = '';
            }


            // -----------------------------------------
            // SAVE ELEMENT
            // -----------------------------------------

            layout.fields[f.key] = item;
        });


        // =====================================================
        // SAVE BACKGROUND AREAS / SHAPES
        // =====================================================

        backgroundAreas.forEach(function(area) {

            const el =
                document.getElementById(
                    area.id
                );

            if (!el) {
                return;
            }


            const visible =
                el.style.display !== 'none';


            layout.fields[area.id] = {

                id:
                    area.id,

                name:
                    area.name ||
                    'Background Area',

                type:
                    'shape',

                x:
                    parseFloat(
                        el.style.left
                    ) || 0,

                y:
                    parseFloat(
                        el.style.top
                    ) || 0,

                width:
                    parseFloat(
                        el.style.width
                    ) || 0,

                height:
                    parseFloat(
                        el.style.height
                    ) || 0,

                backgroundColor:
                    el.style.backgroundColor ||
                    area.backgroundColor ||
                    '#9e1b32',

                opacity:
                    el.style.opacity === ''
                        ? 1
                        : parseFloat(
                            el.style.opacity
                        ),

                borderRadius:
                    parseFloat(
                        el.style.borderRadius
                    ) || 0,

                visible:
                    visible
            };
        });


        // =====================================================
        // SAVE DUPLICATED FIELDS (logo copies, extra text, etc.)
        // =====================================================

        fieldClones.forEach(function (c) {

            const el =
                document.getElementById(c.id);

            if (!el) {
                return;
            }

            const item = {

                cloneOf: c.baseKey,

                type: c.isImage ? 'image' : 'text',

                x: parseFloat(el.style.left) || 0,

                y: parseFloat(el.style.top) || 0,

                visible: el.style.display !== 'none'
            };

            if (c.isImage) {

                item.width =
                    parseFloat(el.style.width) || 0;

                item.height =
                    parseFloat(el.style.height) || 0;

                item.src =
                    el.src || '';

            } else {

                item.text =
                    el.textContent || '';

                item.fontSize =
                    parseFloat(el.style.fontSize) || 14;

                item.color =
                    el.style.color || '';

                item.fontWeight =
                    el.style.fontWeight || '400';
            }

            layout.fields[c.id] = item;
        });


        return layout;
    }


    // =========================================================
    // EXPORT LAYOUT
    // =========================================================

    const exportLayoutBtn =
        document.getElementById(
            'exportLayoutBtn'
        );

    if (exportLayoutBtn) {

        exportLayoutBtn.addEventListener(
            'click',
            function () {

                const layout =
                    buildLayoutJSON();

                const blob =
                    new Blob(
                        [
                            JSON.stringify(
                                layout,
                                null,
                                2
                            )
                        ],
                        {
                            type:
                                'application/json'
                        }
                    );

                const url =
                    URL.createObjectURL(
                        blob
                    );

                const a =
                    document.createElement(
                        'a'
                    );

                a.href = url;

                a.download =
                    'idcard-layout.json';

                a.click();

                URL.revokeObjectURL(url);
            }
        );
    }


    // =========================================================
    // SAVE ID CARD TO DATABASE
    // =========================================================

    const saveLayoutBtn =
        document.getElementById(
            'saveLayoutBtn'
        );


    if (saveLayoutBtn) {

        saveLayoutBtn.addEventListener(
            'click',
            async function () {

                try {

                    // -------------------------------------
                    // BUILD LAYOUT
                    // -------------------------------------

                    const layout =
                        buildLayoutJSON();


                    console.log(
                        'LAYOUT BEING SAVED:',
                        layout
                    );


                    // -------------------------------------
                    // CSRF
                    // -------------------------------------

                    const csrfElement =
                        document.querySelector(
                            'meta[name="csrf-token"]'
                        );


                    if (!csrfElement) {

                        alert(
                            'CSRF token not found.'
                        );

                        return;
                    }


                    const csrfToken =
                        csrfElement.getAttribute(
                            'content'
                        );


                    // -------------------------------------
                    // BACKGROUND
                    // -------------------------------------

                   let background =
                        card.style.backgroundImage ||
                        '';

                    // Extract the raw URL string from url("...") or url('...')
                    background = background.replace(/^url\((['"]?)(.*?)\1\)$/gi, '$2');

                    // Fix duplicate domain prefixes if present
                    background = background.replace(/(https?:\/\/[^\/]+\/storage\/)+/gi, '$1');


                    // -------------------------------------
                    // DATA
                    // -------------------------------------

                    const data = {

                        name:
                            'Default ID Card',

                        orientation:
                            CARD_W >= CARD_H
                                ? 'horizontal'
                                : 'vertical',

                        card_width:
                            CARD_W,

                        card_height:
                            CARD_H,

                        background:
                            background,

                        layout:
                            layout
                    };


                    console.log(
                        'DATA SENT TO SERVER:',
                        data
                    );


                    // -------------------------------------
                    // BUTTON
                    // -------------------------------------

                    saveLayoutBtn.disabled =
                        true;

                    saveLayoutBtn.innerHTML =
                        '⏳ Saving...';


                    // -------------------------------------
                    // SEND TO LARAVEL
                    // -------------------------------------

                    const response =
                        await fetch(
                            "{{ route('mainidcard.store') }}",
                            {

                                method:
                                    'POST',

                                headers: {

                                    'Content-Type':
                                        'application/json',

                                    'X-CSRF-TOKEN':
                                        csrfToken,

                                    'Accept':
                                        'application/json'
                                },

                                body:
                                    JSON.stringify(
                                        data
                                    )
                            }
                        );


                    // -------------------------------------
                    // READ RESPONSE
                    // -------------------------------------

                    const result =
                        await response.json();


                    console.log(
                        'SERVER RESPONSE:',
                        result
                    );


                    // -------------------------------------
                    // SUCCESS
                    // -------------------------------------

                    if (
                        response.ok &&
                        result.success
                    ) {

                        alert(
                            result.message ||
                            'ID Card saved successfully.'
                        );

                    } else {

                        alert(
                            result.message ||
                            'Unable to save ID Card.'
                        );
                    }


                } catch (error) {

                    console.error(
                        'SAVE ERROR:',
                        error
                    );

                    alert(
                        'Something went wrong while saving ID Card.'
                    );


                } finally {

                    saveLayoutBtn.disabled =
                        false;

                    saveLayoutBtn.innerHTML =
                        '💾 Save ID Card';
                }

            }
        );
    }


    // =========================================================
    // DOWNLOAD PNG
    // =========================================================

    const downloadBtn =
        document.getElementById(
            'downloadBtn'
        );


    if (downloadBtn) {

        downloadBtn.addEventListener(
            'click',
            function () {

                const previousTransform =
                    card.style.transform;

                card.style.transform =
                    'none';


                html2canvas(
                    card,
                    {
                        scale: 3,
                        useCORS: true,
                        backgroundColor: null
                    }
                )
                .then(function (canvas) {

                    card.style.transform =
                        previousTransform;


                    const link =
                        document.createElement(
                            'a'
                        );

                    link.download =
                        'id-card.png';

                    link.href =
                        canvas.toDataURL(
                            'image/png'
                        );

                    link.click();

                })
                .catch(function (error) {

                    card.style.transform =
                        previousTransform;

                    console.error(error);

                    alert(
                        'Could not export image.'
                    );
                });
            }
        );
    }


})();
</script>

<script>
// =========================================================
// FLOATING TOOLBAR
// Mirrors clicks to the original header buttons so all
// existing save/export/download logic is reused as-is.
// =========================================================
(function () {

    function mirrorClick(floatId, originalId) {

        const floatBtn = document.getElementById(floatId);
        const originalBtn = document.getElementById(originalId);

        if (!floatBtn || !originalBtn) {
            return;
        }

        floatBtn.addEventListener('click', function () {
            originalBtn.click();
        });
    }

    mirrorClick('exportLayoutBtnFloat', 'exportLayoutBtn');
    mirrorClick('saveLayoutBtnFloat', 'saveLayoutBtn');
    mirrorClick('downloadBtnFloat', 'downloadBtn');

})();
</script>


<script>
document.getElementById('bgUpload').addEventListener('change', function () {

    const file = this.files[0];

    if (!file) {
        return;
    }

    const orientationSelect = document.getElementById('orientationSelect');

    if (!orientationSelect) {
        console.error('orientationSelect not found');
        return;
    }

    const orientation = orientationSelect.value;

    console.log('Selected file:', file);
    console.log('Orientation:', orientation);

    // Create FormData
    const formData = new FormData();

    formData.append('image', file);
    formData.append('orientation', orientation);

    // Debug FormData
    console.log('----- FormData -----');

    for (const [key, value] of formData.entries()) {

        if (value instanceof File) {
            console.log(key, {
                name: value.name,
                size: value.size,
                type: value.type
            });
        } else {
            console.log(key, value);
        }
    }

    console.log('-------------------');

    // Disable input while uploading
    this.disabled = true;

    fetch("{{ route('id-card.upload-design') }}", {
        method: 'POST',

        headers: {
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content'),

            'Accept': 'application/json'
        },

        body: formData
    })
    .then(async response => {

        const data = await response.json();

        console.log('HTTP Status:', response.status);
        console.log('Server Response:', data);

        if (!response.ok) {

            if (data.errors) {
                console.error('Validation Errors:', data.errors);
            }

            throw new Error(
                data.message || 'Upload failed.'
            );
        }

        return data;
    })
    .then(data => {

        if (data.success) {

            console.log('Upload successful:', data);

            alert(data.message);

            // Update preview if element exists
            const cardBackground =
                document.getElementById('cardBackground');

            if (cardBackground && data.url) {

                cardBackground.style.backgroundImage =
                    `url("${data.url}")`;

                cardBackground.style.backgroundSize = 'cover';
                cardBackground.style.backgroundPosition = 'center';
            }

            // Optional: show returned information
            console.log('Sample ID:', data.sample_id);
            console.log('Image URL:', data.url);
            console.log('Image size:', data.size);
            console.log('Orientation:', data.orientation);
            const orientation = data.orientation;

             window.location.href =
            `https://dev.infotasks.com/idcard-editor?orientation=${encodeURIComponent(orientation)}`;


        }

    })
    .catch(error => {

        console.error('Upload Error:', error);

        alert(error.message || 'Something went wrong while uploading.');

    })
    .finally(() => {

        document.getElementById('bgUpload').disabled = false;

    });

});
</script>

@endsection
