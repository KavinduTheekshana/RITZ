<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #8DC63F 0%, #7AB532 100%);
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
            background-color: #ffffff;
        }
        .content h3 {
            color: #8DC63F;
            margin-bottom: 20px;
        }
        .company-info {
            background-color: #f8faf9;
            border-left: 4px solid #8DC63F;
            padding: 20px;
            margin: 20px 0;
        }
        .services-list {
            background-color: #f8faf9;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .services-list ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .services-list li {
            margin-bottom: 8px;
            color: #555;
        }
        .cta-section {
            background-color: #f8faf9;
            padding: 25px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }
        .cta-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #8DC63F;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin-top: 15px;
        }
        .cta-button:hover {
            background-color: #7AB532;
        }
        .footer {
            background-color: #8DC63F;
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .footer p {
            margin: 5px 0;
            font-size: 14px;
        }
        .footer a {
            color: #ffffff;
            text-decoration: underline;
        }
        .social-links {
            margin-top: 20px;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
        }
        .divider {
            height: 2px;
            background: linear-gradient(to right, #8DC63F, #7AB532, #8DC63F);
            margin: 30px 0;
        }
        .confidential-notice {
            font-size: 12px;
            color: #888;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
        }
        .highlight-box {
            background-color: #e8f5e9;
            border: 1px solid #8DC63F;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h2>RITZ Accounting Services</h2>
            <p>Professional Tax & Accounting Solutions</p>
        </div>
        
        <!-- Main Content -->
        <div class="content">
            <p>Dear {{ $companyName }},</p>

            <h3>Your Company Engagement Letter is Ready for Review</h3>

            <p>We are delighted to confirm our engagement to provide comprehensive accounting and tax services to your company. This letter sets out the terms of our professional relationship.</p>

            <div class="company-info">
                <strong>Company: {{ $companyName }}</strong><br>
                <p style="margin-top: 10px;">This engagement covers all agreed professional services for your company's accounting and tax requirements.</p>
            </div>

            <div class="services-list">
                <h3 style="color: #8DC63F; margin-top: 0;">Our Services Include:</h3>
                <ul>
                    <li>Annual accounts preparation and filing</li>
                    <li>Corporation tax computation and returns</li>
                    <li>VAT returns and compliance</li>
                    <li>Payroll services and RTI submissions</li>
                    <li>Company secretarial services</li>
                    <li>Management accounts and reporting</li>
                    <li>Tax planning and advisory services</li>
                    <li>HMRC correspondence and representation</li>
                </ul>
            </div>

            <div class="divider"></div>

            <div class="cta-section">
                <h3 style="color: #8DC63F; margin-top: 0;">Action Required</h3>
                <p>Please review the engagement letter carefully. Once you're satisfied with the terms, you can sign it electronically through your secure client portal.</p>
                <a href="{{ url('/client/engagement') }}" class="cta-button">Access Client Portal</a>
            </div>

            <div class="highlight-box">
                <strong>Why Choose RITZ Accounting?</strong>
                <p style="margin-top: 10px; margin-bottom: 0;">
                    ✓ Experienced chartered accountants<br>
                    ✓ Proactive tax planning approach<br>
                    ✓ Fixed fee arrangements available<br>
                    ✓ Year-round support and advice
                </p>
            </div>

            <p><strong>Next Steps:</strong></p>
            <ol style="color: #555;">
                <li>Review the engagement letter terms</li>
                <li>Sign electronically via your client portal</li>
                <li>Return one signed copy for our records</li>
            </ol>

            <p>If you have any questions regarding this engagement letter or need clarification on any terms, please don't hesitate to contact us. We're here to help.</p>

            <p>We look forward to a successful partnership and helping your business achieve its financial goals.</p>

            <p style="margin-top: 30px;">
                <strong>Best regards,</strong><br>
                The RITZ Accounting Team
            </p>

            <div class="confidential-notice">
                This email and any attachments are confidential and intended solely for the addressee. 
                If you have received this email in error, please notify us immediately and delete it from your system.
            </div>
        </div>

        <!-- Footer Section -->
        <div class="footer">
            <p><strong>RITZ Accounting</strong></p>
            <p>Trusted Accounting Services Across the UK</p>
            <p>📧 info@ritzaccounting.com | 📞 +44 (0) 123 456 7890</p>
            
            <div class="social-links">
                <a href="https://www.facebook.com/profile.php?id=61574686905951" target="_blank">Facebook</a> |
                <a href="https://www.instagram.com/ritz_acct/" target="_blank">Instagram</a> |
                <a href="{{ url('/') }}" target="_blank">Website</a>
            </div>
            
            <p style="margin-top: 20px; font-size: 12px;">
                © {{ date('Y') }} RITZ Accounting. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>