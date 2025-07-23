<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 40px;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
        }
        h3 {
            color: #34495e;
            margin-top: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .sub-section {
            margin-top: 15px;
            font-weight: bold;
        }
        ul {
            margin-left: 20px;
        }
        .signature-section {
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-line {
            margin-top: 40px;
            border-bottom: 1px solid #000;
            width: 300px;
        }
        .company-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ENGAGEMENT LETTER - SELF ASSESSMENT</h1>
    </div>

    <p>Date: {{ now()->format('d F Y') }}</p>

    <div class="company-details">
        <p><strong>To:</strong><br>
        {{ $client->title ?? '' }} {{ $client->first_name }} {{ $client->middle_name ?? '' }} {{ $client->last_name }}<br>
        {{ $client->postal_address ?? '' }}</p>
        
        <p><strong>Email:</strong> {{ $selfAssessment->self_assessment_email ?? $client->email }}<br>
        <strong>Telephone:</strong> {{ $selfAssessment->self_assessment_telephone ?? $client->mobile_number ?? $client->telephone_number ?? 'N/A' }}</p>
    </div>

    <p>Dear {{ $client->title ?? '' }} {{ $client->last_name }},</p>

    <h2>ENGAGEMENT FOR SELF ASSESSMENT TAX SERVICES</h2>

    <p>We are pleased to confirm our acceptance and understanding of this engagement to provide self assessment tax services to you.</p>

    <p>This letter sets out the basis on which we will act for you, including details of our responsibilities and yours.</p>

    <h3>1. YOUR RESPONSIBILITIES</h3>
    <ul>
        <li>To provide us with all information necessary for dealing with your affairs</li>
        <li>To provide information in sufficient time for your tax return to be completed and submitted by the filing deadline</li>
        <li>To respond promptly to our requests for information</li>
        <li>To notify us of any changes in your circumstances that could affect your tax position</li>
        <li>To keep and retain adequate records to support the entries in your tax return</li>
        <li>To forward to us any correspondence received from HMRC without delay</li>
    </ul>

    <h3>2. OUR RESPONSIBILITIES</h3>
    <p>We will:</p>
    <ul>
        <li>Prepare your self assessment tax return based on the information and explanations you provide</li>
        <li>Calculate your tax liability or refund due</li>
        <li>Submit your tax return to HMRC once approved by you</li>
        <li>Advise you when tax payments are due</li>
        <li>Deal with HMRC on your behalf where you have authorised us to do so</li>
        <li>Provide tax planning advice where appropriate</li>
    </ul>

    <h3>3. SCOPE OF SERVICES</h3>
    <p>Our services will include:</p>
    <ul>
        <li>Preparation and submission of your annual self assessment tax return</li>
        <li>Calculation of income tax and national insurance liabilities</li>
        <li>Advice on tax payments and payment on account</li>
        <li>Basic tax planning advice</li>
        <li>Correspondence with HMRC on routine matters</li>
    </ul>

    <h3>4. LIMITATION OF LIABILITY</h3>
    <p>We will not be liable for any loss, damage or cost arising from our compliance with statutory or regulatory obligations.</p>
    
    <p>You agree that you will not bring any claim in connection with services we provide to you against any of our partners or employees personally.</p>

    <h3>5. FEES</h3>
    <p>Our fees will be based on the time spent and complexity of work involved. We will notify you if any circumstances arise which are likely to lead to a significant increase in our fee estimate.</p>

    <h3>6. CONFIDENTIALITY</h3>
    <p>We confirm that all information provided will be treated with strict confidentiality.</p>

    <h3>7. DATA PROTECTION</h3>
    <p>We are committed to respecting the personal data of all our clients. We will only use your personal information to provide the services outlined in this engagement letter.</p>

    <h3>8. PROFESSIONAL INDEMNITY INSURANCE</h3>
    <p>We maintain professional indemnity insurance in respect of all our work.</p>

    <h3>9. TERM</h3>
    <p>This engagement will continue until terminated by either party with 30 days written notice.</p>

    <div class="signature-section">
        <p><strong>ACCEPTANCE OF TERMS</strong></p>
        <p>Please confirm your acceptance of these terms by signing and returning a copy of this letter.</p>

        <p>For and on behalf of {{ $client->full_name }}</p>

        <div class="signature-line"></div>
        <p>Signature</p>

        <div class="signature-line"></div>
        <p>Name (Please Print)</p>

        <div class="signature-line"></div>
        <p>Date</p>

        <br><br>
        <p>Yours sincerely,</p>
        <p><strong>RITZ ACCOUNTING SERVICES</strong></p>
    </div>
</body>
</html>