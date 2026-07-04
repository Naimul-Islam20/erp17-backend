<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Panel' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('Website.png') }}">
    <style>
        :root {
            --primary: #3ba100;
            --primary-hover: #4db60f;
            --primary-soft: #e9f8df;
            --secondary: #54575a;
            --secondary-soft: #f0f1f2;
            --text: #0f172a;
            --muted: #64748b;
            --muted-strong: #475569;
            --border: #dbe4ef;
            --border-strong: #c7d4e5;
            --surface: rgba(255, 255, 255, 0.92);
            --surface-strong: #ffffff;
            --surface-soft: #f8fbff;
            --bg: #eef3f9;
            --shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
            --shadow-soft: 0 12px 34px rgba(15, 23, 42, 0.08);
            --radius-xl: 28px;
            --radius-lg: 22px;
            --radius-md: 16px;
            --radius-sm: 12px;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            min-height: 100%;
            font-family: Arial, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(233, 167, 14, 0.09), transparent 30%),
                linear-gradient(180deg, #f9fbfe 0%, var(--bg) 100%);
            overflow-x: hidden;
        }

        a,
        button,
        input,
        select,
        textarea {
            transition: all 0.18s ease;
        }

        .wrap {
            min-height: 100vh;
            display: block;
        }

        .layout-topbar {
            position: sticky;
            top: 0;
            z-index: 95;
            display: grid;
            grid-template-columns: 250px minmax(0, 1fr);
            background: rgba(255, 255, 255, 0.86);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(199, 212, 229, 0.8);
        }

        .topbar-sidebar {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            min-height: 68px;
            height: 68px;
            padding: 0 18px;
            border-right: 1px solid rgba(199, 212, 229, 0.8);
            overflow: hidden;
        }

        .topbar-sidebar img {
            position: static;
            height: 44px;
            width: auto;
            display: block;
            clip-path: inset(12% 0 12% 0);
            transform: scale(2.2);
            transform-origin: center center;
        }

        .top-header {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 0 18px;
            min-height: 68px;
        }

        .top-header-left,
        .top-header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
            position: relative;
            z-index: 1;
        }

        .top-header-left {
            flex: 1;
        }

        .top-header-right {
            margin-left: auto;
        }

        .top-header-title {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            min-width: 0;
        }

        .top-header-logo {
            height: 44px;
            width: auto;
            display: block;
            flex-shrink: 0;
        }

        .top-header-copy {
            display: grid;
            gap: 1px;
            min-width: 0;
        }

        .mobile-header-logo {
            display: none;
        }

        .top-header-eyebrow {
            display: none;
        }

        .top-header-heading {
            font-size: 15px;
            font-weight: 700;
            color: var(--secondary);
            white-space: nowrap;
        }

        .header-chip {
            display: none;
        }

        .menu-toggle,
        .sidebar-close {
            display: none;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 0;
            background: transparent;
            color: var(--secondary);
            font-size: 20px;
            cursor: pointer;
            box-shadow: none;
        }

        .body-grid {
            display: grid;
            grid-template-columns: 250px minmax(0, 1fr);
            gap: 0;
            padding: 0;
            min-height: calc(100vh - 68px);
            align-items: stretch;
        }

        .body-grid>* {
            min-width: 0;
        }

        aside {
            position: relative;
            top: 0;
            min-height: 100%;
            height: auto;
            padding: 14px 14px 18px;
            border-radius: 0;
            background: var(--secondary);
            color: #d9e2f3;
            box-shadow: none;
            border-right: 1px solid rgba(255, 255, 255, 0.08);
            overflow: auto;
        }

        .sidebar-top-logo,
        .mobile-sidebar-logo {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-top-logo img,
        .mobile-sidebar-logo img {
            height: 108px;
            width: auto;
            display: block;
            clip-path: inset(28% 0 28% 0);
            transform: scale(1.28);
            transform-origin: center center;
        }

        .mobile-sidebar-head,
        .mobile-sidebar-logo {
            display: none;
        }

        .mobile-sidebar-head {
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 0 4px 14px;
            margin-bottom: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .mobile-sidebar-logo {
            display: none;
        }

        aside nav {
            display: grid;
            gap: 6px;
        }

        .main-panel {
            min-width: 0;
            display: flex;
            flex-direction: column;
            min-height: calc(100vh - 68px);
        }

        aside a {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 42px;
            padding: 10px 12px;
            border-radius: 10px;
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.01em;
            border: 1px solid transparent;
            background: transparent;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: currentColor;
        }

        .nav-icon svg {
            width: 20px;
            height: 20px;
            display: block;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        aside a:hover {
            transform: none;
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        aside a.active {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
            box-shadow: none;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.48);
            backdrop-filter: blur(4px);
            z-index: 110;
        }

        main {
            min-width: 0;
            width: 100%;
            max-width: 100%;
            padding: 18px;
        }

        .content-shell {
            display: grid;
            gap: 14px;
            width: 100%;
            max-width: 100%;
            min-width: 0;
        }

        .flash,
        .flash-warning {
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid;
            box-shadow: var(--shadow-soft);
            font-weight: 600;
        }

        .flash {
            background: #ecfdf3;
            color: #166534;
            border-color: #b7efc7;
        }

        .flash-warning {
            background: #fff8e7;
            color: #8a5a00;
            border-color: #f3d081;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 4px;
            flex-wrap: wrap;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
            line-height: 1.1;
            color: #101938;
            letter-spacing: -0.02em;
        }

        .card {
            background: var(--surface);
            border: 1px solid rgba(199, 212, 229, 0.78);
            border-radius: 20px;
            padding: 18px;
            box-shadow: 0 10px 26px rgba(15, 23, 42, 0.06);
            backdrop-filter: blur(8px);
            width: 100%;
            max-width: 100%;
            min-width: 0;
            overflow: hidden;
        }

        .grid {
            display: grid;
            gap: 16px;
        }

        .grid-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .stat-card {
            position: relative;
        }

        .stat-label {
            margin: 0 0 10px;
            color: var(--muted-strong);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .stat-value {
            margin: 0;
            font-size: 34px;
            line-height: 1;
            font-weight: 700;
            color: #101938;
        }

        .stat-meta {
            margin-top: 6px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.45;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 9px 14px;
            border-radius: 10px;
            border: 1px solid transparent;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            box-shadow: none;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
            box-shadow: 0 8px 18px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #fff;
            box-shadow: 0 8px 18px rgba(220, 38, 38, 0.16);
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #f87171 0%, #dc2626 100%);
        }

        .btn-muted {
            background: #eef2f8;
            color: #223055;
            border-color: #d4deeb;
        }

        .btn-muted:hover {
            background: #e5ebf4;
        }

        .btn-outline {
            background: #fff;
            color: var(--secondary);
            border-color: var(--border-strong);
        }

        .btn-outline:hover {
            border-color: rgba(30, 30, 109, 0.35);
            background: #f8fbff;
        }

        .btn-soft {
            background: var(--primary-soft);
            color: var(--secondary);
            border-color: rgba(233, 167, 14, 0.35);
        }

        .btn-soft:hover {
            background: #fff0bc;
        }

        .logout-form {
            margin: 0;
        }

        .action-group {
            display: inline-flex;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
            flex-wrap: nowrap;
            white-space: nowrap;
        }

        .actions-cell {
            white-space: nowrap;
        }

        .icon-action-btn {
            width: 38px;
            min-width: 38px;
            height: 38px;
            min-height: 38px;
            padding: 0;
            border-radius: 10px;
        }

        .icon-action-btn svg {
            width: 18px;
            height: 18px;
            display: block;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .table-wrap {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior-x: contain;
            touch-action: pan-x pinch-zoom;
            border: 1px solid rgba(199, 212, 229, 0.82);
            border-radius: 16px;
            background: var(--surface-strong);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.85);
        }

        .list-shell {
            width: 100%;
            max-width: 100%;
            min-width: 0;
        }

        .table-wrap-flat {
            border-radius: 0;
            box-shadow: none;
        }

        .mobile-sync-scroll {
            display: none;
        }

        table {
            width: 100%;
            min-width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: transparent;
            table-layout: auto;
        }

        thead th {
            position: sticky;
            top: 0;
            z-index: 1;
            background: #f8fbff;
            color: var(--muted-strong);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 11px;
        }

        .table-wrap thead th:last-child {
            right: 0;
            z-index: 4;
            width: 1%;
            min-width: max-content;
            padding-left: 10px;
            padding-right: 8px;
            text-align: right;
            box-shadow: -10px 0 16px -14px rgba(15, 23, 42, 0.35);
        }

        th,
        td {
            padding: 13px 14px;
            text-align: left;
            vertical-align: top;
            white-space: nowrap;
            border-bottom: 1px solid #e5edf6;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        tbody tr:nth-child(even) {
            background: rgba(248, 251, 255, 0.72);
        }

        .table-wrap tbody td.actions-cell {
            position: sticky;
            right: 0;
            z-index: 2;
            width: 1%;
            min-width: max-content;
            padding-left: 10px;
            padding-right: 8px;
            text-align: right;
            background: #fff;
            box-shadow: -10px 0 16px -14px rgba(15, 23, 42, 0.35);
        }

        .table-wrap tbody tr:nth-child(even) td.actions-cell {
            background: #f8fbff;
        }

        .status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 28px;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .status-unread {
            background: #e8fff1;
            color: #0f7a3f;
            border-color: #b7efc7;
        }

        .status-read {
            background: #eef2f8;
            color: #475569;
            border-color: #d8e1ee;
        }

        .status-review {
            background: transparent;
            color: inherit;
            border-color: #d8e1ee;
        }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 11px 13px;
            background: #fff;
            color: var(--text);
            font-size: 14px;
            outline: none;
            box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.03);
        }

        input:hover,
        select:hover,
        textarea:hover {
            border-color: var(--border-strong);
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(59, 161, 0, 0.16);
        }

        textarea {
            resize: vertical;
            min-height: 96px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.01em;
            color: #26324d;
        }

        .error {
            margin-top: 7px;
            color: #b91c1c;
            font-size: 13px;
            font-weight: 600;
        }

        .detail-list {
            display: grid;
            gap: 12px;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 180px minmax(0, 1fr);
            gap: 16px;
            align-items: start;
            padding: 13px 14px;
            border-radius: 14px;
            border: 1px solid rgba(199, 212, 229, 0.8);
            background: linear-gradient(180deg, #fff 0%, #f9fbff 100%);
        }

        .detail-label {
            color: var(--muted-strong);
            font-weight: 700;
        }

        .detail-value {
            color: var(--text);
            word-break: break-word;
            white-space: normal;
            line-height: 1.6;
        }

        .helper-text {
            display: block;
            margin-top: 8px;
            color: var(--muted);
            font-size: 13px;
        }

        .builder-list {
            display: grid;
            gap: 14px;
            margin-top: 16px;
        }

        .builder-card {
            padding: 15px;
            border-radius: 16px;
            border: 1px solid rgba(199, 212, 229, 0.82);
            background: linear-gradient(180deg, #ffffff 0%, var(--surface-soft) 100%);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .builder-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 14px;
        }

        .builder-card-title {
            font-weight: 700;
            color: var(--secondary);
        }

        .builder-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-left: auto;
        }

        .builder-toolbar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 16px;
        }

        .block-preview {
            margin-top: 12px;
            max-width: 240px;
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid rgba(199, 212, 229, 0.9);
            background: #fff;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        }

        .block-preview img {
            width: 100%;
            height: auto;
            display: block;
        }

        .empty-builder {
            padding: 20px;
            border: 1px dashed var(--border-strong);
            border-radius: 18px;
            color: var(--muted);
            background: rgba(255, 255, 255, 0.7);
        }

        .point-input-actions {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-top: 8px;
            width: 100%;
        }

        .point-input-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: start;
            gap: 10px;
        }

        .point-input-row textarea {
            min-width: 0;
            min-height: 46px;
            height: 46px;
            resize: vertical;
        }

        .point-input-addrow {
            display: flex;
            justify-content: flex-start;
            margin-top: 10px;
        }

        .icon-delete-btn {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #fecaca;
            border-radius: 12px;
            background: #fff;
            color: #dc2626;
            cursor: pointer;
        }

        .icon-delete-btn:hover {
            background: #fff1f2;
            color: #b91c1c;
        }

        .icon-delete-btn svg {
            width: 18px;
            height: 18px;
            display: block;
        }

        .icon-add-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 13px;
            border-radius: 12px;
            border: 1px solid rgba(233, 167, 14, 0.35);
            background: var(--primary-soft);
            color: var(--secondary);
            font-weight: 700;
            cursor: pointer;
        }

        .icon-add-btn:hover {
            background: #fff0bc;
        }

        .icon-add-symbol {
            font-size: 18px;
            line-height: 1;
        }

        @media (max-width: 1180px) {
            .layout-topbar {
                grid-template-columns: 230px minmax(0, 1fr);
            }

            .body-grid {
                grid-template-columns: 230px minmax(0, 1fr);
            }

            .header h1 {
                font-size: 28px;
            }
        }

        @media (max-width: 900px) {

            .menu-toggle,
            .sidebar-close {
                display: inline-flex;
                width: 48px;
                height: 48px;
                font-size: 30px;
            }

            .header-chip {
                display: none;
            }

            .body-grid {
                grid-template-columns: 1fr;
                padding: 0;
                min-height: auto;
            }

            .layout-topbar {
                grid-template-columns: 1fr;
            }

            .topbar-sidebar {
                display: none;
            }

            .mobile-header-logo {
                position: absolute;
                top: 50%;
                left: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 140px;
                height: 48px;
                overflow: hidden;
                transform: translate(-50%, -50%);
                pointer-events: none;
            }

            .mobile-header-logo img {
                height: 92px;
                width: auto;
                display: block;
                clip-path: inset(32% 0 32% 0);
                transform: scale(1.18);
                transform-origin: center center;
            }

            aside {
                position: fixed;
                top: 0;
                left: 0;
                width: min(86vw, 300px);
                height: 100vh;
                min-height: 100vh;
                border-radius: 0 18px 18px 0;
                z-index: 120;
                transform: translateX(-105%);
                transition: transform 0.25s ease;
                padding-top: 18px;
                border-right: 1px solid rgba(255, 255, 255, 0.08);
            }

            .mobile-sidebar-logo {
                display: flex;
                flex: 1;
                height: 48px;
                align-items: center;
                justify-content: center;
                overflow: hidden;
            }

            .mobile-sidebar-logo img {
                height: 92px;
                clip-path: inset(32% 0 32% 0);
                transform: scale(1.18);
                transform-origin: center center;
            }

            .mobile-sidebar-head {
                display: flex;
                background: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(14px);
                margin: -18px -14px 14px;
                min-height: 48px;
                padding: 0 14px;
                border-bottom: 1px solid rgba(199, 212, 229, 0.8);
                overflow: hidden;
            }

            .main-panel {
                min-height: 100vh;
            }

            .sidebar-open aside {
                transform: translateX(0);
            }

            .sidebar-open .sidebar-overlay {
                display: block;
            }

            main {
                width: 100%;
            }

            .table-wrap {
                overflow-x: scroll;
                scrollbar-width: thin;
                scrollbar-color: #94a3b8 #e5e7eb;
                margin-bottom: 4px;
                touch-action: pan-x pinch-zoom;
            }

            .table-wrap::-webkit-scrollbar {
                height: 12px;
            }

            .table-wrap::-webkit-scrollbar-track {
                background: #e5e7eb;
                border-radius: 10px;
            }

            .table-wrap::-webkit-scrollbar-thumb {
                background: #94a3b8;
                border-radius: 10px;
            }

        }

        @media (max-width: 768px) {
            .top-header {
                padding: 0 14px;
            }

            .top-header-heading {
                font-size: 14px;
            }

            .top-header-logo {
                height: 38px;
            }

            .grid-2 {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .detail-row {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .header h1 {
                font-size: 22px;
            }

            .card {
                padding: 16px;
                border-radius: 18px;
            }

            .builder-card {
                padding: 14px;
            }

            .table-wrap .actions-cell {
                width: auto;
                min-width: max-content;
                padding-left: 4px;
                padding-right: 4px;
            }

            .table-wrap .action-group {
                justify-content: flex-start;
                gap: 2px;
            }

            .table-wrap .btn.icon-action-btn {
                width: 24px;
                min-width: 24px;
                height: 24px;
                min-height: 24px;
                padding: 0;
                border: 0;
                border-radius: 0;
                background: transparent;
                box-shadow: none;
            }

            .table-wrap .btn.icon-action-btn:hover {
                transform: none;
                background: transparent;
                box-shadow: none;
            }

            .table-wrap .btn.icon-action-btn svg {
                width: 16px;
                height: 16px;
            }

            .table-wrap .btn-danger.icon-action-btn {
                color: #dc2626;
            }

            .table-wrap thead th:last-child {
                width: auto;
                min-width: max-content;
                padding-left: 6px;
                padding-right: 6px;
                font-size: 0;
                letter-spacing: 0;
            }

            table {
                width: auto;
                min-width: 760px;
            }

            main {
                padding: 14px;
            }
        }

        @media (max-width: 640px) {
            .top-header-copy {
                display: none;
            }

            .top-header-right {
                gap: 8px;
            }

            .body-grid {
                padding: 0;
            }

            .btn {
                min-height: 40px;
                padding: 9px 12px;
                font-size: 13px;
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <div class="layout-topbar">
            <div class="topbar-sidebar">
                <div class="sidebar-top-logo">
                    <img src="{{ asset('Website.png') }}" alt="ERP17">
                </div>
            </div>
            <div class="top-header">
                <div class="top-header-left">
                    <button type="button" class="menu-toggle" id="menuToggle" aria-label="Open menu">☰</button>
                </div>
                <div class="mobile-header-logo" aria-hidden="true">
                    <img src="{{ asset('Website.png') }}" alt="ERP17">
                </div>
                <div class="top-header-right">
                    <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <div class="body-grid">
            <aside>
                <div class="mobile-sidebar-head">
                    <div class="mobile-sidebar-logo">
                        <img src="{{ asset('Website.png') }}" alt="ERP17">
                    </div>
                    <button type="button" class="sidebar-close" id="sidebarClose" aria-label="Close menu">×</button>
                </div>
                <nav>
                    <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M3 13 12 4l9 9"></path>
                                <path d="M5 10v10h14V10"></path>
                            </svg>
                        </span>
                        <span>Dashboard</span>
                    </a>
                    <a class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </span>
                        <span>Users</span>
                    </a>
                    <a class="{{ request()->routeIs('admin.quote-requests.*') ? 'active' : '' }}" href="{{ route('admin.quote-requests.index') }}">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                <path d="M8 9h8"></path>
                                <path d="M8 13h5"></path>
                            </svg>
                        </span>
                        <span>Quote Requests</span>
                    </a>
                    <a class="{{ request()->routeIs('admin.expert-sessions.*') ? 'active' : '' }}" href="{{ route('admin.expert-sessions.index') }}">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                <path d="M8 9h8"></path>
                                <path d="M8 13h6"></path>
                            </svg>
                        </span>
                        <span>Contact</span>
                    </a>
                    <a class="{{ request()->routeIs('admin.newsletters.*') ? 'active' : '' }}" href="{{ route('admin.newsletters.index') }}">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                        </span>
                        <span>Newsletter</span>
                    </a>
                    <a class="{{ request()->routeIs('admin.newsletter-categories.*') ? 'active' : '' }}" href="{{ route('admin.newsletter-categories.index') }}">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M4 7h16"></path>
                                <path d="M4 12h10"></path>
                                <path d="M4 17h7"></path>
                            </svg>
                        </span>
                        <span>Categorys</span>
                    </a>
                    <a class="{{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}" href="{{ route('admin.blogs.index') }}">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                <path d="M8 7h8"></path>
                                <path d="M8 11h8"></path>
                            </svg>
                        </span>
                        <span>Blog</span>
                    </a>
                    <a class="{{ request()->routeIs('admin.educations.*') ? 'active' : '' }}" href="{{ route('admin.educations.index') }}">
                        <span class="nav-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 3 3 7.5 12 12l9-4.5L12 3Z"></path>
                                <path d="M3 12.5 12 17l9-4.5"></path>
                                <path d="M3 17.5 12 22l9-4.5"></path>
                            </svg>
                        </span>
                        <span>Education</span>
                    </a>
                </nav>
            </aside>
            <div class="main-panel">
                <main>
                    <div class="content-shell">
                        @if (session('status'))
                        <div class="flash">{{ session('status') }}</div>
                        @endif
                        @if (session('warning'))
                        <div class="flash-warning">{{ session('warning') }}</div>
                        @endif

                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </div>
    <script>
        (() => {
            const root = document.querySelector('.wrap');
            const menuToggle = document.getElementById('menuToggle');
            const overlay = document.getElementById('sidebarOverlay');
            const sidebarClose = document.getElementById('sidebarClose');

            if (!root || !menuToggle || !overlay) return;

            const closeSidebar = () => root.classList.remove('sidebar-open');
            const openSidebar = () => root.classList.add('sidebar-open');

            menuToggle.addEventListener('click', () => {
                if (root.classList.contains('sidebar-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });

            overlay.addEventListener('click', closeSidebar);
            sidebarClose?.addEventListener('click', closeSidebar);
            window.addEventListener('resize', () => {
                if (window.innerWidth > 900) closeSidebar();
            });

            const enableTouchHorizontalScroll = (wrap) => {
                if (!wrap || wrap.dataset.touchScrollBound === '1') {
                    return;
                }

                wrap.dataset.touchScrollBound = '1';

                let startX = 0;
                let startY = 0;
                let startScrollLeft = 0;

                wrap.addEventListener('touchstart', (event) => {
                    const touch = event.touches[0];
                    if (!touch) return;

                    startX = touch.clientX;
                    startY = touch.clientY;
                    startScrollLeft = wrap.scrollLeft;
                }, {
                    passive: true
                });

                wrap.addEventListener('touchmove', (event) => {
                    const touch = event.touches[0];
                    if (!touch) return;

                    const deltaX = touch.clientX - startX;
                    const deltaY = touch.clientY - startY;
                    const canScrollX = wrap.scrollWidth > wrap.clientWidth;

                    if (!canScrollX || Math.abs(deltaX) <= Math.abs(deltaY)) {
                        return;
                    }

                    wrap.scrollLeft = startScrollLeft - deltaX;
                    event.preventDefault();
                }, {
                    passive: false
                });
            };

            const getRequiredTableWidth = (table) => {
                const rows = Array.from(table.querySelectorAll('tr'));
                const columnWidths = [];

                rows.forEach((row) => {
                    Array.from(row.children).forEach((cell, index) => {
                        const styles = window.getComputedStyle(cell);
                        const horizontalPadding = parseFloat(styles.paddingLeft || '0') + parseFloat(styles.paddingRight || '0');
                        const cellWidth = Math.ceil(cell.scrollWidth + horizontalPadding + 8);

                        columnWidths[index] = Math.max(columnWidths[index] || 0, cellWidth);
                    });
                });

                return Math.max(760, columnWidths.reduce((sum, width) => sum + width, 0));
            };

            const setupMobileTableScroll = () => {
                document.querySelectorAll('.table-wrap').forEach((wrap) => {
                    const table = wrap.querySelector('table');
                    if (!table) return;

                    enableTouchHorizontalScroll(wrap);

                    const active = window.innerWidth <= 900;

                    if (active) {
                        const requiredWidth = getRequiredTableWidth(table);
                        table.style.width = `${requiredWidth}px`;
                        table.style.minWidth = `${requiredWidth}px`;
                    } else {
                        table.style.width = '100%';
                        table.style.minWidth = '100%';
                    }
                });

                document.querySelectorAll('.mobile-sync-scroll').forEach((element) => {
                    element.remove();
                });
            };

            setupMobileTableScroll();
            window.addEventListener('resize', setupMobileTableScroll);
        })();
    </script>
</body>

</html>