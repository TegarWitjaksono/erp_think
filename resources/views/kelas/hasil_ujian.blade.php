@extends('depan.layout_after')

@section('konten')
    @php
        // PHP implementation of detectContentType function - maintained as is
        if (!function_exists('detectContentType')) {
            function detectContentType($content)
            {
                if (!$content) {
                    return [
                        'type' => 'text',
                        'content' => '',
                    ];
                }

                if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $content)) {
                    return [
                        'type' => 'image',
                        'content' => $content,
                    ];
                }

                if (strpos($content, 'http://') === 0 || strpos($content, 'https://') === 0) {
                    return [
                        'type' => 'url',
                        'content' => $content,
                    ];
                }

                if (preg_match('/\.(pdf|doc|docx|xls|xlsx|ppt|pptx)$/i', $content)) {
                    return [
                        'type' => 'document',
                        'content' => $content,
                    ];
                }

                if (preg_match('/\.(mp3|wav|ogg)$/i', $content)) {
                    return [
                        'type' => 'audio',
                        'content' => $content,
                    ];
                }

                return [
                    'type' => 'text',
                    'content' => $content,
                ];
            }
        }

        if (!function_exists('getDocumentIcon')) {
            function getDocumentIcon($extension)
            {
                switch (strtolower($extension)) {
                    case 'pdf':
                        return 'pdf';
                    case 'doc':
                    case 'docx':
                        return 'word';
                    case 'xls':
                    case 'xlsx':
                        return 'excel';
                    case 'ppt':
                    case 'pptx':
                        return 'powerpoint';
                    default:
                        return 'alt';
                }
            }
        }
    @endphp

    <style>
        :root {
            --primary-color: #191970;
            --primary-light: #4895ef;
            --primary-dark: #3f37c9;
            --secondary-color: #f8f9fa;
            --text-color: #2b2d42;
            --accent-color: #4cc9f0;
            --success-color: #4ade80;
            --danger-color: #f43f5e;
            --warning-color: #fb923c;
            --border-color: #e9ecef;
            --shadow-color: rgba(0, 0, 0, 0.05);
            --card-bg: #ffffff;
            --font-family: 'Inter', 'Segoe UI', Roboto, -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            font-family: var(--font-family);
            background-color: #f1f5f9;
            color: var(--text-color);
        }

        /* Main Container */
        .results-container {
            max-width: 950px;
            margin: 30px auto 50px;
            padding: 0 15px;
        }

        /* Header Section */
        .results-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 35px 25px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            box-shadow: 0 10px 25px rgba(79, 97, 238, 0.2);
            position: relative;
            overflow: hidden;
        }

        .results-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .results-title {
            color: white;
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }

        .results-subtitle {
            color: rgba(255, 255, 255, 0.95);
            font-size: 18px;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        /* Score Section */
        .score-container {
            display: flex;
            justify-content: center;
            margin: -60px auto 40px;
            gap: 24px;
            position: relative;
            z-index: 2;
            flex-wrap: wrap;
        }

        .score-box {
            text-align: center;
            padding: 24px;
            border-radius: 16px;
            width: 180px;
            box-shadow: 0 10px 20px var(--shadow-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .score-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .score-box::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }

        .score-box:hover::after {
            opacity: 1;
        }

        .score-value {
            font-size: 48px;
            font-weight: 800;
            margin: 5px 0;
            line-height: 1;
        }

        .score-label {
            font-size: 16px;
            font-weight: 500;
            opacity: 0.9;
            margin-top: 8px;
        }

        .main-score {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            width: 200px;
        }

        .correct-score {
            background: linear-gradient(135deg, #10b981, var(--success-color));
            color: white;
        }

        .incorrect-score {
            background: linear-gradient(135deg, #ef4444, var(--danger-color));
            color: white;
        }

        /* Info badges */
        .score-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Results Detail Section */
        .detailed-results {
            margin-top: 30px;
            padding: 30px;
            background-color: var(--card-bg);
            border-radius: 16px;
            box-shadow: 0 5px 20px var(--shadow-color);
        }

        .detailed-results-title {
            color: var(--text-color);
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
            position: relative;
            display: flex;
            align-items: center;
        }

        .detailed-results-title::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 22px;
            background: linear-gradient(to bottom, var(--primary-color), var(--primary-light));
            border-radius: 4px;
            margin-right: 12px;
        }

        /* Question Card */
        .question-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            border: 1px solid var(--border-color);
        }

        .question-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.06);
        }

        .question-text {
            font-weight: 600;
            font-size: 17px;
            margin-bottom: 18px;
            color: var(--text-color);
            padding-left: 32px;
            position: relative;
        }

        .question-text::before {
            content: "Q";
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 24px;
            height: 24px;
            background-color: var(--primary-color);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 13px;
            font-weight: 700;
        }

        .question-content {
            padding: 10px 0;
            margin-bottom: 20px;
        }

        /* Question Options */
        .options-list {
            list-style-type: none;
            padding: 0;
            margin-top: 15px;
        }

        .option-item {
            display: flex;
            align-items: flex-start;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }

        .option-content {
            flex-grow: 1;
            margin: 0 15px;
        }

        .option-item::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.8) 0%, rgba(255, 255, 255, 0) 80%);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }

        .option-item:hover::after {
            opacity: 0.5;
        }

        .option-correct {
            background-color: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .option-incorrect {
            background-color: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .option-neutral {
            background-color: var(--secondary-color);
            border: 1px solid rgba(233, 236, 239, 0.8);
        }

        .option-letter {
            font-weight: 600;
            margin-right: 15px;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 50%;
            font-size: 14px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .option-icon {
            margin-left: auto;
            font-size: 20px;
        }

        .option-correct .option-icon {
            color: #10b981;
            animation: pulse 1.5s infinite;
        }

        .option-incorrect .option-icon {
            color: #ef4444;
            animation: shake 0.5s ease-in-out;
        }

        /* Image Option */
        .option-image-container {
            max-width: 200px;
            margin: 5px 0;
        }

        .answer-image {
            width: 100%;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Audio Option */
        .option-audio-container {
            background: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            padding: 12px;
            margin: 5px 0;
        }

        .answer-audio {
            width: 100%;
            height: 36px;
            border-radius: 18px;
        }

        /* Document Option */
        .option-document-container {
            margin: 5px 0;
        }

        .document-preview {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            padding: 12px;
        }

        .document-icon {
            font-size: 24px;
            margin-right: 12px;
            color: var(--primary-color);
        }

        .document-info {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .document-name {
            font-size: 0.9rem;
            color: var(--text-color);
        }

        .document-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 15px;
            background: var(--primary-color);
            color: white;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .document-link:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            color: white;
            text-decoration: none;
        }

        /* URL Option */
        .option-url-container {
            margin: 5px 0;
        }

        .url-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.5);
            color: var(--primary-color);
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .url-link:hover {
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }

        /* Animation keyframes */
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.15);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20%,
            60% {
                transform: translateX(-3px);
            }

            40%,
            80% {
                transform: translateX(3px);
            }
        }

        /* Back Button */
        .back-button {
            display: block;
            width: 220px;
            margin: 40px auto 20px;
            padding: 14px 24px;
            text-align: center;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            border: none;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 16px rgba(79, 97, 238, 0.25);
            position: relative;
            overflow: hidden;
        }

        .back-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0));
            transition: left 0.7s ease;
        }

        .back-button:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            color: white;
            text-decoration: none;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(79, 97, 238, 0.35);
        }

        .back-button:hover::before {
            left: 100%;
        }

        .back-button i {
            margin-right: 8px;
        }

        /* Image container */
        .image-container {
            max-width: 100%;
            margin: 16px 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .image-container:hover {
            transform: scale(1.03);
        }

        .question-image,
        .answer-image {
            max-width: 100%;
            border-radius: 8px;
            display: block;
            transition: filter 0.3s ease;
        }

        .question-image:hover,
        .answer-image:hover {
            filter: brightness(1.05);
        }

        /* Size adjustment for question and answer images */
        .question-image,
        .answer-image {
            max-width: 250px;
            height: auto;
        }

        /* Progress indicator */
        .progress-container {
            margin: 25px 0;
            padding: 6px;
            background-color: #f1f5f9;
            border-radius: 16px;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.03);
        }

        .progress-bar {
            height: 10px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-light));
            border-radius: 8px;
            transition: width 0.8s ease;
            position: relative;
        }

        .progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 20px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3));
            border-radius: 0 8px 8px 0;
            animation: shimmer 1.5s infinite;
        }

        @keyframes shimmer {
            0% {
                opacity: 0.5;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.5;
            }
        }

        .progress-info {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            color: var(--text-color);
            font-size: 14px;
            font-weight: 500;
        }

        /* Fade-in animation */
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Question number badge */
        .question-number {
            position: absolute;
            top: -12px;
            right: -12px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 10px rgba(79, 97, 238, 0.3);
            border: 2px solid white;
        }

        /* Filter options */
        .filter-options {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-button {
            padding: 8px 16px;
            background-color: var(--secondary-color);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-color);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-button:hover,
        .filter-button.active {
            background-color: var(--primary-color);
            color: white;
        }

        /* Summary box */
        .summary-box {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .summary-item {
            flex: 1;
            min-width: 150px;
            text-align: center;
        }

        .summary-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .summary-label {
            font-size: 14px;
            color: #64748b;
            margin-top: 5px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .score-container {
                flex-direction: column;
                align-items: center;
                margin-top: -40px;
            }

            .score-box {
                width: 85%;
                max-width: 220px;
            }

            .results-container {
                margin: 15px;
                padding: 0 10px;
            }

            .question-card {
                padding: 20px 16px;
            }

            .option-item {
                padding: 12px 14px;
            }

            .results-header {
                padding: 25px 15px;
                margin-bottom: 50px;
            }

            .back-button {
                width: 100%;
            }

            .detailed-results {
                padding: 20px 15px;
            }

            .results-title {
                font-size: 26px;
            }

            .filter-options {
                overflow-x: auto;
                padding-bottom: 5px;
                justify-content: flex-start;
                width: 100%;
            }
        }

        /* Print styles */
        @media print {
            .back-button {
                display: none;
            }

            .results-container {
                margin: 0;
                width: 100%;
            }

            .question-card {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .score-container {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }

            .score-box {
                border: 1px solid #ddd;
            }
        }

        /* Document and Audio Styles */
        .document-container {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 16px;
            padding: 25px;
            margin: 20px 0;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .document-preview-wrapper {
            position: relative;
            width: 100%;
            height: 600px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            background: #fff;
            margin-bottom: 1rem;
        }

        .document-preview {
            width: 100%;
            height: 100%;
            border: none;
        }

        .document-preview-controls {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .preview-control-btn {
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 8px;
            color: var(--text-color);
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            backdrop-filter: blur(4px);
            transition: all 0.2s ease;
        }

        .preview-control-btn:hover {
            background: white;
            transform: translateY(-1px);
        }

        .document-info {
            text-align: center;
            padding: 8px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            font-size: 0.9rem;
            color: var(--text-color);
        }

        .audio-container {
            background: linear-gradient(145deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .audio-player-title {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 12px;
        }

        .question-audio,
        .answer-audio {
            width: 100%;
            border-radius: 50px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .answer-audio {
            max-width: 250px;
        }

        .audio-info {
            text-align: center;
            margin-top: 10px;
            color: #64748b;
        }

        /* Document type icons */
        .document-type-icon {
            font-size: 2.5em;
            margin-bottom: 15px;
        }

        .document-type-icon.pdf {
            color: #dc2626;
        }

        .document-type-icon.word {
            color: #2563eb;
        }

        .document-type-icon.excel {
            color: #16a34a;
        }

        .document-type-icon.powerpoint {
            color: #ea580c;
        }

        /* URL container styles */
        .url-container {
            background: linear-gradient(145deg, #f8fafc 0%, #f1f5f9 100%);
            border-radius: 12px;
            padding: 15px;
            margin: 10px 0;
            text-align: center;
        }

        .url-preview-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--hover-color) 100%);
            color: white;
            border-radius: 20px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(25, 25, 112, 0.15);
        }

        .url-preview-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(25, 25, 112, 0.25);
            color: white;
            text-decoration: none;
        }

        .url-info {
            margin-top: 8px;
            color: #64748b;
            font-size: 0.9rem;
            word-break: break-all;
            padding: 6px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 6px;
        }

        .question-text-content {
            font-size: 1.1rem;
            color: var(--text-color);
            padding: 1rem;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            border-left: 4px solid var(--primary-color);
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .document-preview-wrapper {
                height: 400px;
            }
        }

        @media (max-width: 576px) {
            .document-preview-wrapper {
                height: 300px;
            }
        }
    </style>

    <div class="results-container">
        <div class="results-header">
            <h1 class="results-title">Hasil Ujian</h1>
            <p class="results-subtitle">
                @if (isset($materi) && $materi)
                    {{ $materi->judul }}
                @elseif(isset($quiz) && $quiz)
                    {{ $quiz->nama_quiz }}
                @else
                    Ujian
                @endif
            </p>
        </div>

        <div class="score-container">
            <div class="score-box main-score fade-in">
                <div class="score-value">{{ $result->score }}</div>
                <div class="score-label">Nilai Akhir</div>
                @if ($result->score >= 80)
                    <div class="score-badge">Excellent!</div>
                @elseif($result->score >= 60)
                    <div class="score-badge">Good!</div>
                @endif
            </div>
            <div class="score-box correct-score fade-in">
                <div class="score-value">{{ $result->benar }}</div>
                <div class="score-label">Jawaban Benar</div>
            </div>
            <div class="score-box incorrect-score fade-in">
                <div class="score-value">{{ $result->salah }}</div>
                <div class="score-label">Jawaban Salah</div>
            </div>
        </div>

        <div class="detailed-results">
            <h2 class="detailed-results-title">
                Detail Jawaban
            </h2>

            <!-- Summary box -->
            <div class="summary-box">
                <div class="summary-item">
                    <div class="summary-value">{{ $result->benar + $result->salah }}</div>
                    <div class="summary-label">Total Soal</div>
                </div>
                <div class="summary-item">
                    <div class="summary-value">{{ round(($result->benar / ($result->benar + $result->salah)) * 100) }}%
                    </div>
                    <div class="summary-label">Persentase Benar</div>
                </div>
                <div class="summary-item">
                    <div class="summary-value">{{ date('d M Y') }}</div>
                    <div class="summary-label">Tanggal Ujian</div>
                </div>
            </div>

            <!-- Filter options -->
            <div class="filter-options">
                <button class="filter-button active" data-filter="all">Semua</button>
                <button class="filter-button" data-filter="correct">Benar</button>
                <button class="filter-button" data-filter="incorrect">Salah</button>
            </div>

            <!-- Progress indicator -->
            <div class="progress-container">
                <div class="progress-bar" style="width: {{ ($result->benar / ($result->benar + $result->salah)) * 100 }}%">
                </div>
                <div class="progress-info">
                    <span>{{ $result->benar }} benar</span>
                    <span>{{ round(($result->benar / ($result->benar + $result->salah)) * 100) }}%</span>
                </div>
            </div>

            @foreach ($detailedResults as $index => $questionResult)
                @php
                    $isCorrect = $questionResult['jawaban_siswa'] == $questionResult['jawaban_benar'];
                    $filterClass = $isCorrect ? 'filter-correct' : 'filter-incorrect';
                @endphp

                <div class="question-card fade-in {{ $filterClass }}" data-index="{{ $index + 1 }}">
                    <div class="question-number">{{ $index + 1 }}</div>
                    <div class="question-text">{{ $isCorrect ? 'Jawaban Benar' : 'Jawaban Salah' }}</div>

                    <div class="question-content">
                        @php
                            $soalContent = detectContentType($questionResult['soal']);
                        @endphp


                        <!-- Display question text first if available -->
                        @if (isset($questionResult['soal_text']))
                            <div class="question-text-content mb-3">
                                {{ $questionResult['soal_text'] }}
                            </div>
                        @endif

                        @if ($soalContent['type'] === 'image')
                            <div class="image-container">
                                <img src="{{ asset($soalContent['content']) }}" class="question-image"
                                    alt="Soal {{ $index + 1 }}">
                            </div>
                        @elseif($soalContent['type'] === 'audio')
                            <div class="audio-container">
                                <div class="audio-player-title">
                                    <i class="fas fa-music"></i> Audio Soal
                                </div>
                                <audio controls class="question-audio" controlsList="nodownload">
                                    <source src="{{ asset($soalContent['content']) }}"
                                        type="audio/{{ pathinfo($soalContent['content'], PATHINFO_EXTENSION) }}">
                                    Browser Anda tidak mendukung pemutaran audio.
                                </audio>
                                <div class="audio-info mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i>
                                        Dengarkan audio di atas untuk menjawab pertanyaan
                                    </small>
                                </div>
                            </div>
                        @elseif($soalContent['type'] === 'document')
                            <div class="document-container">
                                @php
                                    $fileExtension = pathinfo($soalContent['content'], PATHINFO_EXTENSION);
                                    $isPdf = strtolower($fileExtension) === 'pdf';
                                    $fileName = basename($soalContent['content']);
                                @endphp

                                @if ($isPdf)
                                    <div class="document-preview-wrapper">
                                        <iframe src="{{ asset($soalContent['content']) }}"
                                            class="document-preview"></iframe>
                                        <div class="document-preview-controls">
                                            <button class="preview-control-btn"
                                                onclick="toggleFullScreen(this.parentElement.previousElementSibling)">
                                                <i class="fas fa-expand"></i> Layar Penuh
                                            </button>
                                            <a href="{{ asset($soalContent['content']) }}" class="preview-control-btn"
                                                target="_blank">
                                                <i class="fas fa-external-link-alt"></i> Buka di Tab Baru
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center p-4">
                                        <i
                                            class="fas fa-file-{{ getDocumentIcon($fileExtension) }} document-type-icon {{ strtolower($fileExtension) }}"></i>
                                        <h5 class="mt-3">{{ $fileName }}</h5>
                                        <p class="text-muted small mb-3">Dokumen ini tidak dapat ditampilkan secara langsung
                                        </p>
                                        <a href="{{ asset($soalContent['content']) }}" class="btn btn-primary"
                                            target="_blank">
                                            <i class="fas fa-download mr-2"></i> Download Dokumen
                                        </a>
                                    </div>
                                @endif

                                <div class="document-info mt-3">
                                    <i class="fas fa-info-circle"></i>
                                    <span>{{ $fileName }}</span>
                                </div>
                            </div>
                        @elseif($soalContent['type'] === 'url')
                            <div class="url-container">
                                <div class="url-icon mb-3">
                                    <i class="fas fa-link fa-2x"></i>
                                </div>
                                <a href="{{ $soalContent['content'] }}" target="_blank" class="url-preview-button">
                                    <i class="fas fa-external-link-alt"></i> Lihat Soal
                                </a>
                                <div class="url-info mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    {{ $soalContent['content'] }}
                                </div>
                            </div>
                        @else
                            <p>{{ $soalContent['content'] }}</p>
                        @endif
                    </div>

                    <ul class="options-list">
                        @for ($i = 1; $i <= 4; $i++)
                            @php
                                $optionContent = detectContentType($questionResult["pilihan_$i"]);
                                $optionLetter = chr(64 + $i);
                                $isCorrectAnswer = $i == $questionResult['jawaban_benar'];
                                $isSelectedAnswer = $i == $questionResult['jawaban_siswa'];

                                if ($isSelectedAnswer && $isCorrectAnswer) {
                                    $optionClass = 'option-correct';
                                    $icon = '<i class="fas fa-check-circle option-icon"></i>';
                                } elseif ($isSelectedAnswer && !$isCorrectAnswer) {
                                    $optionClass = 'option-incorrect';
                                    $icon = '<i class="fas fa-times-circle option-icon"></i>';
                                } elseif ($isCorrectAnswer) {
                                    $optionClass = 'option-correct';
                                    $icon = '<i class="fas fa-check-circle option-icon"></i>';
                                } else {
                                    $optionClass = 'option-neutral';
                                    $icon = '';
                                }
                            @endphp

                            <li class="option-item {{ $optionClass }}">
                                <span class="option-letter">{{ $optionLetter }}</span>

                                <div class="option-content">
                                    @if ($optionContent['type'] === 'image')
                                        <div class="option-image-container">
                                            <img src="{{ asset($optionContent['content']) }}" class="answer-image"
                                                alt="Pilihan {{ $optionLetter }}">
                                        </div>
                                    @elseif($optionContent['type'] === 'audio')
                                        <div class="option-audio-container">
                                            <div class="audio-player-title">
                                                <i class="fas fa-music"></i> Audio Pilihan {{ $optionLetter }}
                                            </div>
                                            <audio controls class="answer-audio" controlsList="nodownload">
                                                <source src="{{ asset($optionContent['content']) }}"
                                                    type="audio/{{ pathinfo($optionContent['content'], PATHINFO_EXTENSION) }}">
                                                Browser Anda tidak mendukung pemutaran audio.
                                            </audio>
                                        </div>
                                    @elseif($optionContent['type'] === 'document')
                                        <div class="option-document-container">
                                            @php
                                                $fileExtension = pathinfo(
                                                    $optionContent['content'],
                                                    PATHINFO_EXTENSION,
                                                );
                                                $fileName = basename($optionContent['content']);
                                            @endphp
                                            <div class="document-preview">
                                                <i
                                                    class="fas fa-file-{{ getDocumentIcon($fileExtension) }} document-icon"></i>
                                                <div class="document-info">
                                                    <span class="document-name">{{ $fileName }}</span>
                                                    <a href="{{ asset($optionContent['content']) }}" target="_blank"
                                                        class="document-link">
                                                        <i class="fas fa-external-link-alt"></i> Buka Dokumen
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($optionContent['type'] === 'url')
                                        <div class="option-url-container">
                                            <a href="{{ $optionContent['content'] }}" target="_blank" class="url-link">
                                                <i class="fas fa-link"></i>
                                                <span>Lihat Pilihan {{ $optionLetter }}</span>
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </div>
                                    @else
                                        <span class="option-text">{{ $optionContent['content'] }}</span>
                                    @endif
                                </div>

                                <span class="option-icon">{!! $icon !!}</span>
                            </li>
                        @endfor
                    </ul>
                </div>
            @endforeach
        </div>

        <a href="{{ route('siswaIn') }}" class="back-button">
            <i class="fas fa-home"></i> Kembali ke Beranda
        </a>
    </div>
@endsection

@section('script')
    <script>
        // Function to detect content type (image, url, or text)
        function detectContentType(content) {
            if (!content) return {
                type: 'text',
                content: ''
            };

            if (/\.(jpg|jpeg|png|gif)$/i.test(content)) {
                return {
                    type: 'image',
                    content: content
                };
            }

            if (/\.(pdf|doc|docx|xls|xlsx|ppt|pptx)$/i.test(content)) {
                return {
                    type: 'document',
                    content: content
                };
            }

            if (/\.(mp3|wav|ogg)$/i.test(content)) {
                return {
                    type: 'audio',
                    content: content
                };
            }

            if (/^https?:\/\//i.test(content)) {
                return {
                    type: 'url',
                    content: content
                };
            }

            return {
                type: 'text',
                content: content
            };
        }


        function getDocumentIcon(extension) {
            const icons = {
                pdf: 'pdf',
                doc: 'word',
                docx: 'word',
                xls: 'excel',
                xlsx: 'excel',
                ppt: 'powerpoint',
                pptx: 'powerpoint'
            };
            return icons[extension.toLowerCase()] || 'file';
        }

        // Add animations and interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Animate score boxes with staggered timing
            const scoreBoxes = document.querySelectorAll('.score-box');
            scoreBoxes.forEach((box, index) => {
                setTimeout(() => {
                    box.classList.add('fade-in');
                }, 300 + (index * 200));
            });

            // Animate question cards on scroll
            const questionCards = document.querySelectorAll('.question-card');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('fade-in');
                        }, 150);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            questionCards.forEach((card) => {
                card.style.opacity = '0';
                observer.observe(card);
            });

            // Filter functionality
            const filterButtons = document.querySelectorAll('.filter-button');

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));

                    // Add active class to clicked button
                    button.classList.add('active');

                    const filter = button.getAttribute('data-filter');

                    // Filter question cards
                    questionCards.forEach(card => {
                        if (filter === 'all') {
                            card.style.display = 'block';
                        } else if (filter === 'correct' && card.classList.contains(
                                'filter-correct')) {
                            card.style.display = 'block';
                        } else if (filter === 'incorrect' && card.classList.contains(
                                'filter-incorrect')) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            // Print functionality
            const printButton = document.createElement('button');
            printButton.innerHTML = '<i class="fas fa-print"></i> Print Results';
            printButton.classList.add('back-button');
            printButton.style.width = '220px';
            printButton.style.marginTop = '20px';
            printButton.style.background = 'linear-gradient(135deg, #64748b, #94a3b8)';

            const backButton = document.querySelector('.back-button');
            backButton.parentNode.insertBefore(printButton, backButton);

            printButton.addEventListener('click', () => {
                window.print();
            });

            // Add option to collapse/expand questions
            questionCards.forEach(card => {
                const questionText = card.querySelector('.question-text');
                const content = card.querySelector('.question-content');
                const optionsList = card.querySelector('.options-list');

                let isCollapsed = false;

                questionText.style.cursor = 'pointer';
                questionText.addEventListener('click', () => {
                    if (isCollapsed) {
                        content.style.display = 'block';
                        optionsList.style.display = 'block';
                        isCollapsed = false;
                    } else {
                        content.style.display = 'none';
                        optionsList.style.display = 'none';
                        isCollapsed = true;
                    }
                });

                // Add toggle indicator
                const toggleIndicator = document.createElement('span');
                toggleIndicator.innerHTML = '<i class="fas fa-chevron-down"></i>';
                toggleIndicator.style.marginLeft = '10px';
                toggleIndicator.style.fontSize = '14px';
                questionText.appendChild(toggleIndicator);
            });

            // Add smooth scrolling to question cards
            const createScrollButtons = () => {
                const scrollTopButton = document.createElement('button');
                scrollTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
                scrollTopButton.classList.add('scroll-top-button');
                scrollTopButton.style.position = 'fixed';
                scrollTopButton.style.bottom = '20px';
                scrollTopButton.style.right = '20px';
                scrollTopButton.style.width = '50px';
                scrollTopButton.style.height = '50px';
                scrollTopButton.style.borderRadius = '50%';
                scrollTopButton.style.backgroundColor = 'var(--primary-color)';
                scrollTopButton.style.color = 'white';
                scrollTopButton.style.border = 'none';
                scrollTopButton.style.boxShadow = '0 4px 10px rgba(0,0,0,0.1)';
                scrollTopButton.style.cursor = 'pointer';
                scrollTopButton.style.display = 'none';
                scrollTopButton.style.zIndex = '999';

                document.body.appendChild(scrollTopButton);

                scrollTopButton.addEventListener('click', () => {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });

                window.addEventListener('scroll', () => {
                    if (window.scrollY > 500) {
                        scrollTopButton.style.display = 'block';
                    } else {
                        scrollTopButton.style.display = 'none';
                    }
                });
            };

            createScrollButtons();

            // Add percentage complete indicator to the progress bar
            const progressBar = document.querySelector('.progress-bar');
            const percentage = ({{ $result->benar }} / ({{ $result->benar }} + {{ $result->salah }})) * 100;

            // Create progress percentage indicator
            const progressPercent = document.createElement('span');
            progressPercent.textContent = `${Math.round(percentage)}%`;
            progressPercent.style.position = 'absolute';
            progressPercent.style.right = '10px';
            progressPercent.style.top = '50%';
            progressPercent.style.transform = 'translateY(-50%)';
            progressPercent.style.color = 'white';
            progressPercent.style.fontSize = '12px';
            progressPercent.style.fontWeight = 'bold';
            progressPercent.style.textShadow = '0 1px 2px rgba(0,0,0,0.2)';

            if (percentage > 30) {
                progressBar.appendChild(progressPercent);
            }
        });

        // Add fullscreen functionality
        function toggleFullScreen(element) {
            if (!document.fullscreenElement) {
                element.requestFullscreen().catch(err => {
                    console.error(`Error attempting to enable full-screen mode: ${err.message}`);
                });
            } else {
                document.exitFullscreen();
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find the Beranda nav link and add the active class
            const berandaLink = document.querySelector('.navbar-nav .nav-item:first-child .nav-link');
            if (berandaLink) {
                berandaLink.classList.add('active');
            }
        });
    </script>
@endsection
