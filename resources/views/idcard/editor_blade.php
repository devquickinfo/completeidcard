@extends('frontend.layout.applayout')
@section('title', 'Template Editor')
@section('content')
{{--
    CONTROLLER CONTRACT (see wiring notes at bottom of this file)
    ------------------------------------------------------------
    Route (GET)  : idcard.template.edit  { mainidcard }  -> this view
    Route (POST) : idcard.template.layout.save { mainidcard } -> saves layout JSON

    Expected variables passed from controller (maps to `mainidcards` table):
      $sample   -> a MainIdCard model instance
                     id, school_id, name, orientation ('vertical'|'horizontal'),
                     card_width, card_height, background, layout (json), is_default
      $layout   -> array|null  decoded $sample->layout (json_decode(..., true)) or null
      $fields   -> array of ['key' => 'student_name', 'label' => 'Student Name']
                   these are the placeholders the student-side renderer will
                   later replace with real data, e.g. {{student_name}}
--}}
@php
    // Cap the on-screen canvas so very large card sizes still fit the editor.
    // Coordinates are still saved as %, so this scale never affects saved data.
    $maxDisplayWidth = 600;
    $scale = $sample->card_width > $maxDisplayWidth ? $maxDisplayWidth / $sample->card_width : 1;
    $displayWidth = (int) round($sample->card_width * $scale);
    $displayHeight = (int) round($sample->card_height * $scale);
@endphp

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-1 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0">Template Editor</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to list
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                {{-- ============ CANVAS ============ --}}
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h3 class="card-title mb-0">
                                Layout Canvas
                                <span class="badge badge-secondary ml-2" id="orientationBadge">
                                    {{ $sample->orientation ?? 'horizontal' }}
                                </span>
                            </h3>
                            <div class="ml-auto">
                                <button type="button" class="btn btn-sm btn-outline-danger" id="clearBtn">
                                    <i class="fas fa-undo"></i> Clear fields
                                </button>
                                <button type="button" class="btn btn-sm btn-primary" id="saveBtn">
                                    <i class="fas fa-save"></i> Save layout
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="canvasWrap" class="canvas-wrap">
                                <div id="canvas" class="canvas"
                                     style="width: {{ $displayWidth }}px; height: {{ $displayHeight }}px;
                                            background-image: url('{{ $sample->background ? asset('storage/' . $sample->background) : '' }}');">
                                    {{-- field elements are injected here by JS --}}
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">
                                Drag fields onto the card. Drag corners to resize, drag the body to move.
                                Click a field to edit its text style on the right.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- ============ SIDEBAR ============ --}}
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Available Fields</h3>
                        </div>
                        <div class="card-body">
                            <div class="field-picker">
                                @foreach($fields as $f)
                                    <button type="button" class="btn btn-outline-primary btn-sm field-add-btn"
                                            data-key="{{ $f['key'] }}" data-label="{{ $f['label'] }}"
                                            data-type="{{ $f['key'] === 'photo' ? 'image' : 'text' }}">
                                        <i class="fas {{ $f['key'] === 'photo' ? 'fa-image' : 'fa-plus' }}"></i>
                                        {{ $f['label'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card" id="propsCard" style="display:none;">
                        <div class="card-header">
                            <h3 class="card-title mb-0">Field Properties</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Label</label>
                                <input type="text" id="propLabel" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="form-group">
                                <label>Font size (px)</label>
                                <input type="number" id="propFontSize" class="form-control form-control-sm" value="14" min="6" max="72">
                            </div>
                            <div class="form-group">
                                <label>Color</label>
                                <input type="color" id="propColor" class="form-control form-control-sm" value="#000000">
                            </div>
                            <div class="form-group">
                                <label>Alignment</label>
                                <select id="propAlign" class="form-control form-control-sm">
                                    <option value="left">Left</option>
                                    <option value="center">Center</option>
                                    <option value="right">Right</option>
                                </select>
                            </div>
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" id="propBold">
                                <label class="form-check-label" for="propBold">Bold</label>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-block" id="removeFieldBtn">
                                <i class="fas fa-trash"></i> Remove field
                            </button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <small class="text-muted">
                                Tip: build this layout once per orientation. When generating cards for
                                all students, each <code>{{'{'}}{{'{'}}field_key{{'}'}}{{'}'}}</code>
                                placeholder is swapped for that student's real value.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
    .canvas-wrap {
        display: flex;
        justify-content: center;
        background: #eceef1;
        padding: 24px;
        border-radius: 4px;
        overflow: auto;
    }
    .canvas {
        position: relative;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border: 1px solid #ccc;
        box-shadow: 0 2px 10px rgba(0,0,0,.15);
        touch-action: none;
    }
    /* Actual size is set inline per-template from card_width/card_height (scaled to fit). */

    .field-el {
        position: absolute;
        min-width: 30px;
        min-height: 16px;
        padding: 2px 4px;
        border: 1px dashed rgba(0,123,255,.6);
        background: rgba(255,255,255,.55);
        cursor: move;
        overflow: hidden;
        white-space: nowrap;
        font-family: Arial, sans-serif;
        line-height: 1.2;
        box-sizing: border-box;
    }
    .field-el.selected { border: 1px solid #007bff; background: rgba(0,123,255,.12); }
    .field-el.field-image {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(200,200,200,.5);
        color: #555;
        font-size: 11px;
        text-align: center;
        white-space: normal;
    }
    .field-picker { display: flex; flex-wrap: wrap; gap: 6px; }
    .field-picker .btn { flex: 0 0 auto; }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/interactjs/1.10.27/interact.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('canvas');
    const canvasWrap = document.getElementById('canvasWrap');
    const saveUrl = @json(route('idcard.template.layout.save', $sample->id));
    const csrfToken = @json(csrf_token());
    let savedLayout = @json($layout ?? []); // decoded from $sample->layout (longtext/json)
    let selectedEl = null;
    let counter = 0;

    // ---------- element factory ----------
    function makeFieldEl(key, label, type, pos) {
        counter++;
        const id = 'f_' + key + '_' + counter;
        const el = document.createElement('div');
        el.className = 'field-el' + (type === 'image' ? ' field-image' : '');
        el.id = id;
        el.dataset.key = key;
        el.dataset.label = label;
        el.dataset.type = type;
        el.innerText = type === 'image' ? label : '{{' + key + '}}';

        const canvasRect = canvas.getBoundingClientRect();
        const defaults = {
            xPct: pos?.xPct ?? 10,
            yPct: pos?.yPct ?? 10,
            wPct: pos?.wPct ?? (type === 'image' ? 30 : 40),
            hPct: pos?.hPct ?? (type === 'image' ? 30 : 10),
            fontSize: pos?.fontSize ?? 14,
            color: pos?.color ?? '#000000',
            align: pos?.align ?? 'left',
            bold: pos?.bold ?? false,
        };
        applyStyleFromPct(el, defaults);
        el.dataset.font = defaults.fontSize;
        el.dataset.color = defaults.color;
        el.dataset.align = defaults.align;
        el.dataset.bold = defaults.bold ? '1' : '0';

        canvas.appendChild(el);
        makeInteractive(el);
        selectField(el);
        return el;
    }

    function applyStyleFromPct(el, p) {
        const cw = canvas.offsetWidth, ch = canvas.offsetHeight;
        el.style.left = (p.xPct / 100 * cw) + 'px';
        el.style.top = (p.yPct / 100 * ch) + 'px';
        el.style.width = (p.wPct / 100 * cw) + 'px';
        el.style.height = (p.hPct / 100 * ch) + 'px';
        el.style.fontSize = (p.fontSize || 14) + 'px';
        el.style.color = p.color || '#000';
        el.style.textAlign = p.align || 'left';
        el.style.fontWeight = p.bold ? 'bold' : 'normal';
    }

    // ---------- drag / resize ----------
    function makeInteractive(el) {
        interact(el)
            .draggable({
                listeners: {
                    move(event) {
                        const t = event.target;
                        const x = (parseFloat(t.style.left) || 0) + event.dx;
                        const y = (parseFloat(t.style.top) || 0) + event.dy;
                        t.style.left = x + 'px';
                        t.style.top = y + 'px';
                    }
                },
                modifiers: [
                    interact.modifiers.restrictRect({ restriction: 'parent', endOnly: false })
                ]
            })
            .resizable({
                edges: { left: true, right: true, bottom: true, top: true },
                listeners: {
                    move(event) {
                        const t = event.target;
                        let x = parseFloat(t.style.left) || 0;
                        let y = parseFloat(t.style.top) || 0;
                        t.style.width = event.rect.width + 'px';
                        t.style.height = event.rect.height + 'px';
                        x += event.deltaRect.left;
                        y += event.deltaRect.top;
                        t.style.left = x + 'px';
                        t.style.top = y + 'px';
                    }
                },
                modifiers: [
                    interact.modifiers.restrictSize({ min: { width: 20, height: 14 } })
                ]
            });

        el.addEventListener('click', function (e) {
            e.stopPropagation();
            selectField(el);
        });
    }

    // ---------- selection & properties panel ----------
    const propsCard = document.getElementById('propsCard');
    const propLabel = document.getElementById('propLabel');
    const propFontSize = document.getElementById('propFontSize');
    const propColor = document.getElementById('propColor');
    const propAlign = document.getElementById('propAlign');
    const propBold = document.getElementById('propBold');

    function selectField(el) {
        if (selectedEl) selectedEl.classList.remove('selected');
        selectedEl = el;
        el.classList.add('selected');
        propsCard.style.display = 'block';
        propLabel.value = el.dataset.label;
        propFontSize.value = el.dataset.font;
        propColor.value = el.dataset.color;
        propAlign.value = el.dataset.align;
        propBold.checked = el.dataset.bold === '1';
    }

    canvas.addEventListener('click', function () {
        if (selectedEl) selectedEl.classList.remove('selected');
        selectedEl = null;
        propsCard.style.display = 'none';
    });

    [propFontSize, propColor, propAlign, propBold].forEach(input => {
        input.addEventListener('input', function () {
            if (!selectedEl) return;
            selectedEl.dataset.font = propFontSize.value;
            selectedEl.dataset.color = propColor.value;
            selectedEl.dataset.align = propAlign.value;
            selectedEl.dataset.bold = propBold.checked ? '1' : '0';
            selectedEl.style.fontSize = propFontSize.value + 'px';
            selectedEl.style.color = propColor.value;
            selectedEl.style.textAlign = propAlign.value;
            selectedEl.style.fontWeight = propBold.checked ? 'bold' : 'normal';
        });
    });

    document.getElementById('removeFieldBtn').addEventListener('click', function () {
        if (!selectedEl) return;
        selectedEl.remove();
        selectedEl = null;
        propsCard.style.display = 'none';
    });

    document.getElementById('clearBtn').addEventListener('click', function () {
        if (!confirm('Remove all fields from the canvas?')) return;
        canvas.querySelectorAll('.field-el').forEach(el => el.remove());
        selectedEl = null;
        propsCard.style.display = 'none';
    });

    // ---------- add field buttons ----------
    document.querySelectorAll('.field-add-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            makeFieldEl(btn.dataset.key, btn.dataset.label, btn.dataset.type, null);
        });
    });

    // ---------- save ----------
    document.getElementById('saveBtn').addEventListener('click', function () {
        const cw = canvas.offsetWidth, ch = canvas.offsetHeight;
        const layout = [];
        canvas.querySelectorAll('.field-el').forEach(el => {
            layout.push({
                key: el.dataset.key,
                label: el.dataset.label,
                type: el.dataset.type,
                xPct: (parseFloat(el.style.left) / cw) * 100,
                yPct: (parseFloat(el.style.top) / ch) * 100,
                wPct: (parseFloat(el.style.width) / cw) * 100,
                hPct: (parseFloat(el.style.height) / ch) * 100,
                fontSize: parseInt(el.dataset.font, 10),
                color: el.dataset.color,
                align: el.dataset.align,
                bold: el.dataset.bold === '1',
            });
        });

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ layout: layout })
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Save layout';
            if (data.success) {
                toastr && toastr.success ? toastr.success('Layout saved') : alert('Layout saved');
            } else {
                alert(data.message || 'Could not save layout');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Save layout';
            alert('Save failed. Check console/network tab.');
        });
    });

    // ---------- load existing layout ----------
    function loadLayout() {
        if (!Array.isArray(savedLayout) || savedLayout.length === 0) return;
        savedLayout.forEach(item => {
            makeFieldEl(item.key, item.label, item.type, item);
        });
        selectedEl = null;
        propsCard.style.display = 'none';
        canvas.querySelectorAll('.field-el.selected').forEach(el => el.classList.remove('selected'));
    }

    // wait for layout+fonts to settle before positioning from %
    window.requestAnimationFrame(() => setTimeout(loadLayout, 50));
});
</script>
@endsection
