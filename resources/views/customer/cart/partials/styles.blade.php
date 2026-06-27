<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Outfit:wght@300;400;500;600;700&display=swap');

    :root {
        --lyb-gold: #b08a42;
        --lyb-gold-light: #faf7f2;
        --lyb-gold-hover: #9c7835;
        --lyb-gold-border: #eadfd6;
        --lyb-dark: #1e1313;
        --lyb-muted: #88746a;
        --lyb-bg: #fdfbf9;
        --premium-shadow: 0 10px 40px rgba(30, 19, 19, 0.03);
        --premium-shadow-hover: 0 20px 50px rgba(30, 19, 19, 0.08);
    }

    body {
        background-color: var(--lyb-bg) !important;
        font-family: 'Inter', sans-serif !important;
    }

    h1, h2, h3, h4, h5, h6, .playfair-text {
        font-family: 'Playfair Display', Georgia, serif !important;
        color: var(--lyb-dark) !important;
        letter-spacing: -0.01em;
    }

    .outfit-font {
        font-family: 'Outfit', sans-serif !important;
    }

    /* Page Hero Compact */
    .page-hero-compact {
        padding: 60px 0 40px;
        background: linear-gradient(180deg, #f7f3eb 0%, var(--lyb-bg) 100%);
        border-bottom: 1px solid rgba(176, 138, 66, 0.08);
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }
    
    .page-hero-compact::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 350px;
        height: 350px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(176, 138, 66, 0.05) 0%, transparent 70%);
        pointer-events: none;
    }

    .page-hero-compact h1 {
        font-size: 3rem;
        margin-bottom: 12px;
        font-weight: 700;
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .page-hero-compact p {
        color: var(--lyb-muted);
        font-size: 16px;
        margin-bottom: 0;
        letter-spacing: 0.5px;
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.1s both;
    }

    /* Progress Checkout Steps */
    .checkout-progress {
        display: flex;
        justify-content: center;
        align-items: center;
        max-width: 600px;
        margin: 25px auto 0;
        position: relative;
        z-index: 2;
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        flex: 1;
        text-align: center;
    }

    .progress-step::after {
        content: '';
        position: absolute;
        top: 15px;
        left: calc(50% + 15px);
        width: calc(100% - 30px);
        height: 2px;
        background-color: var(--lyb-gold-border);
        z-index: 1;
    }

    .progress-step:last-child::after {
        display: none;
    }

    .step-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #fff;
        border: 2px solid var(--lyb-gold-border);
        color: var(--lyb-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 600;
        z-index: 2;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .step-label {
        font-size: 12px;
        font-weight: 500;
        color: var(--lyb-muted);
        margin-top: 8px;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .progress-step.active .step-icon {
        background-color: var(--lyb-dark);
        border-color: var(--lyb-dark);
        color: #fff;
        box-shadow: 0 0 0 4px rgba(30, 19, 19, 0.15);
    }

    .progress-step.active .step-label {
        color: var(--lyb-dark);
        font-weight: 600;
    }

    .progress-step.completed .step-icon {
        background-color: var(--lyb-gold);
        border-color: var(--lyb-gold);
        color: #fff;
    }

    .progress-step.completed .step-label {
        color: var(--lyb-gold);
    }

    .progress-step.completed::after {
        background-color: var(--lyb-gold);
    }

    /* Custom Checkbox Styling */
    .cart-checkbox-wrapper {
        padding-right: 24px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
    }
    
    .cart-item-checkbox {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        width: 24px;
        height: 24px;
        border: 2px solid var(--lyb-gold-border);
        border-radius: 6px; /* Persegi dengan sudut tidak kaku */
        outline: none;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        cursor: pointer;
        background: #fff;
    }

    .cart-item-checkbox:checked {
        background-color: var(--lyb-gold);
        border-color: var(--lyb-gold);
        transform: scale(1.05);
        box-shadow: 0 3px 8px rgba(176, 138, 66, 0.25);
    }

    .cart-item-checkbox:checked::after {
        content: '';
        position: absolute;
        left: 7.5px;
        top: 3.5px;
        width: 5px;
        height: 10px;
        border: solid #fff;
        border-width: 0 2.5px 2.5px 0;
        transform: rotate(45deg);
    }

    .cart-item-checkbox:hover {
        border-color: var(--lyb-gold);
        box-shadow: 0 0 0 4px rgba(176, 138, 66, 0.1);
    }

    /* Select All Bar Styling */
    .cart-select-all-bar {
        background: #fff;
        border: 1px solid rgba(176, 138, 66, 0.12);
        border-radius: 14px;
        padding: 16px 24px;
        box-shadow: var(--premium-shadow);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .cart-select-all-bar:hover {
        border-color: rgba(176, 138, 66, 0.25);
    }

    .select-all-label {
        font-size: 14px;
        font-weight: 600;
        color: var(--lyb-dark);
        cursor: pointer;
        user-select: none;
        margin-bottom: 0;
    }

    /* Cart Cards */
    .cart-items-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .cart-card {
        background: #fff;
        border: 1px solid rgba(176, 138, 66, 0.12);
        border-radius: 20px;
        padding: 24px;
        box-shadow: var(--premium-shadow);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .cart-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, var(--lyb-gold) 0%, #d5b575 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .cart-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--premium-shadow-hover);
        border-color: rgba(176, 138, 66, 0.25);
    }

    .cart-card:hover::before {
        opacity: 1;
    }

    /* Staggered load animation delays */
    .cart-card:nth-child(1) { animation-delay: 0.1s; }
    .cart-card:nth-child(2) { animation-delay: 0.2s; }
    .cart-card:nth-child(3) { animation-delay: 0.3s; }
    .cart-card:nth-child(4) { animation-delay: 0.4s; }
    .cart-card:nth-child(5) { animation-delay: 0.5s; }

    /* Custom remove animation */
    .cart-card.cart-item-removing {
        animation: slideOutLeft 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        pointer-events: none;
    }

    .cart-img-wrapper {
        width: 110px;
        height: 110px;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid rgba(176, 138, 66, 0.1);
        flex-shrink: 0;
        margin-right: 24px;
        position: relative;
    }

    .cart-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .cart-card:hover .cart-img {
        transform: scale(1.06);
    }

    /* Sweep light effect on hover */
    .cart-img-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        left: -75%;
        width: 50%;
        height: 100%;
        background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 100%);
        transform: skewX(-25deg);
        transition: 0.75s;
    }
    
    .cart-card:hover .cart-img-wrapper::after {
        left: 125%;
    }

    .cart-img-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--lyb-gold-light) 0%, #fff 100%);
        color: var(--lyb-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
    }

    .cart-details {
        flex-grow: 1;
        padding-right: 50px;
    }

    .cart-details .category-tag {
        color: var(--lyb-gold);
        text-transform: uppercase;
        font-weight: 700;
        font-size: 9px;
        letter-spacing: 2px;
        display: inline-block;
        margin-bottom: 6px;
        padding: 3px 10px;
        background: rgba(176, 138, 66, 0.05);
        border: 1px solid rgba(176, 138, 66, 0.08);
        border-radius: 6px;
    }

    .cart-details h3 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--lyb-dark);
        line-height: 1.3;
    }

    .cart-details .detail-text {
        margin-bottom: 6px;
        font-size: 13px;
        color: #6e5e56;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cart-details .detail-text i {
        color: var(--lyb-gold);
        font-size: 14px;
    }

    .addon-badge {
        background: #fff;
        color: #5c4e46;
        border: 1px solid rgba(176, 138, 66, 0.15);
        font-size: 11px;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.3s ease;
    }

    .addon-badge:hover {
        border-color: var(--lyb-gold);
        background: var(--lyb-gold-light);
        color: var(--lyb-gold);
    }

    .cart-actions-price {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: space-between;
        min-height: 110px;
        flex-shrink: 0;
        min-width: 170px;
        padding-left: 20px;
        border-left: 1px solid rgba(176, 138, 66, 0.08);
    }

    .cart-price-info {
        text-align: right;
    }

    .cart-price-info .price-label {
        font-size: 9px;
        color: var(--lyb-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
        margin-bottom: 2px;
    }

    .cart-price-info .price-val {
        font-family: 'Playfair Display', Georgia, serif !important;
        font-size: 21px;
        font-weight: 700;
        color: var(--lyb-dark);
        display: block;
    }

    .cart-price-info .dp-val {
        font-size: 12px;
        font-weight: 600;
        color: #c97529;
        display: inline-block;
        padding: 3px 8px;
        background: rgba(212, 131, 59, 0.07);
        border: 1px solid rgba(212, 131, 59, 0.12);
        border-radius: 6px;
        margin-top: 4px;
    }

    .cart-button-row {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 8px;
    }

    .btn-edit-cart {
        background: #fff;
        border: 1.5px solid var(--lyb-gold-border);
        color: var(--lyb-dark);
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
        border-radius: 30px;
        padding: 7px 18px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-edit-cart:hover {
        background: var(--lyb-gold);
        border-color: var(--lyb-gold);
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(176, 138, 66, 0.15);
        transform: translateY(-1px);
    }

    /* Absolute circular trash button (modified inline) */
    .btn-delete-cart {
        background: rgba(217, 83, 79, 0.05);
        border: 1px solid rgba(217, 83, 79, 0.1);
        color: #d9534f;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        font-size: 13px;
        padding: 0;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .btn-delete-cart:hover {
        background: #d9534f;
        color: #fff;
        border-color: #d9534f;
        transform: rotate(10deg) scale(1.1);
        box-shadow: 0 4px 10px rgba(217, 83, 79, 0.2);
    }

    /* Summary Card Styling */
    .summary-card {
        background: #fff;
        border: 1px solid rgba(176, 138, 66, 0.15);
        border-radius: 24px;
        padding: 30px;
        box-shadow: var(--premium-shadow);
        position: sticky;
        top: 100px;
        background: linear-gradient(180deg, #ffffff 0%, #fdfcfb 100%);
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) 0.3s both;
    }

    .summary-card h3 {
        font-size: 21px;
        font-weight: 600;
        margin-bottom: 24px;
        color: var(--lyb-dark);
        border-bottom: 1px dashed var(--lyb-gold-border);
        padding-bottom: 16px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 16px;
        font-size: 14px;
        color: #5c4e46;
        align-items: center;
    }

    .summary-row.total-row {
        border-top: 1.5px solid var(--lyb-gold-border);
        padding-top: 20px;
        margin-top: 20px;
        font-size: 15px;
        font-weight: 700;
        color: var(--lyb-dark);
    }

    .summary-row strong {
        color: var(--lyb-dark);
        font-family: 'Outfit', sans-serif !important;
        font-size: 15px;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .summary-row.total-row strong {
        font-family: 'Playfair Display', Georgia, serif !important;
        font-size: 21px;
        color: var(--lyb-dark);
    }

    .btn-checkout {
        background: linear-gradient(135deg, var(--lyb-dark) 0%, #3a2222 100%);
        color: #fff;
        border: none;
        border-radius: 30px;
        padding: 14px;
        font-weight: 600;
        font-size: 15px;
        letter-spacing: 0.5px;
        width: 100%;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        margin-top: 15px;
        box-shadow: 0 6px 20px rgba(30, 19, 19, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-checkout i {
        transition: transform 0.3s ease;
    }

    .btn-checkout:hover:not(:disabled) {
        background: linear-gradient(135deg, #321c1c 0%, #4f3131 100%);
        box-shadow: 0 10px 25px rgba(30, 19, 19, 0.25);
        transform: translateY(-2px);
    }

    .btn-checkout:hover:not(:disabled) i {
        transform: translateX(4px);
    }

    .btn-checkout:disabled {
        background: #dbd6d2;
        color: #a59c97;
        cursor: not-allowed;
        box-shadow: none;
    }

    .checkout-footer-text {
        font-size: 11px;
        color: var(--lyb-muted);
        text-align: center;
        margin-top: 20px;
        line-height: 1.5;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .checkout-footer-text i {
        color: var(--lyb-gold);
        font-size: 14px;
    }

    /* Back link animation */
    .back-link {
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .back-link:hover {
        color: var(--lyb-gold) !important;
        transform: translateX(-4px);
    }

    /* Trust seals */
    .trust-seals {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 15px;
        opacity: 0.6;
        transition: opacity 0.3s ease;
    }
    
    .trust-seals:hover {
        opacity: 0.9;
    }

    .trust-seals i {
        font-size: 20px;
        color: var(--lyb-muted);
    }

    /* Empty Cart View Styling */
    .empty-cart-container {
        padding: 80px 40px;
        background: #ffffff;
        border: 1px solid rgba(176, 138, 66, 0.12);
        border-radius: 24px;
        box-shadow: var(--premium-shadow);
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    .empty-cart-icon {
        font-size: 80px;
        color: rgba(176, 138, 66, 0.18);
        display: inline-block;
        margin-bottom: 24px;
        position: relative;
    }

    .empty-cart-sparkle {
        position: absolute;
        color: var(--lyb-gold);
        font-size: 20px;
        opacity: 0.6;
        animation: pulseGold 2s infinite ease-in-out;
    }

    .empty-cart-sparkle-1 { top: 0; right: -10px; animation-delay: 0.3s; }
    .empty-cart-sparkle-2 { bottom: 10px; left: -15px; animation-delay: 0.7s; }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideOutLeft {
        0% {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
        40% {
            opacity: 0.5;
            transform: translateX(50px) scale(0.98);
        }
        100% {
            opacity: 0;
            transform: translateX(-150px) scale(0.9);
            height: 0;
            padding: 0;
            margin: 0;
            border: 0;
        }
    }

    @keyframes pulseGold {
        0%, 100% {
            transform: scale(1);
            opacity: 0.5;
        }
        50% {
            transform: scale(1.2);
            opacity: 0.9;
        }
    }

    /* Floating bag animation for empty cart */
    .empty-cart-icon i {
        display: inline-block;
        animation: floatBag 3s ease-in-out infinite;
    }

    @keyframes floatBag {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    /* Responsive Mobile styling */
    @media (max-width: 991px) {
        .summary-card {
            position: relative;
            top: 0;
            margin-top: 20px;
        }
    }

    @media (max-width: 768px) {
        .page-hero-compact h1 {
            font-size: 2.2rem;
        }

        .checkout-progress {
            margin-top: 20px;
        }

        .step-label {
            font-size: 10px;
        }

        .cart-card {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px;
            padding-top: 50px;
        }

        .cart-checkbox-wrapper {
            position: absolute;
            top: 20px;
            left: 20px;
            padding-right: 0;
        }

        .cart-img-wrapper {
            width: 100%;
            height: 180px;
            margin-right: 0;
            margin-bottom: 20px;
            border-radius: 12px;
        }

        .cart-details {
            width: 100%;
            padding-right: 0;
            margin-bottom: 20px;
        }

        .cart-actions-price {
            width: 100%;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            min-height: auto;
            padding-left: 0;
            border-left: none;
            border-top: 1px solid rgba(176, 138, 66, 0.08);
            padding-top: 16px;
        }

        .cart-price-info {
            text-align: left;
            margin-bottom: 0;
        }

        .cart-button-row {
            margin-top: 0;
        }

    }
</style>
