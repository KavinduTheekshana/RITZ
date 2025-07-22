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
        .content ul {
            background-color: #f8faf9;
            border-left: 4px solid #8DC63F;
            padding: 20px 20px 20px 40px;
            margin: 20px 0;
        }
        .content ul li {
            margin-bottom: 10px;
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
            <p>Dear Client,</p>

            <h3>Your Self Assessment Engagement Letter is Ready</h3>

            <p>We are pleased to confirm our engagement to provide self assessment tax services. This engagement letter outlines the terms of our professional relationship.</p>

            <p><strong>What's included in our engagement:</strong></p>
            <ul>
                <li>Comprehensive self assessment tax services</li>
                <li>Expert guidance on tax planning and compliance</li>
                <li>Timely submission of your tax returns</li>
                <li>Year-round support for tax-related queries</li>
                <li>Professional representation with HMRC when needed</li>
            </ul>

            <div class="divider"></div>

            <div class="cta-section">
                <h3 style="color: #8DC63F; margin-top: 0;">Next Steps</h3>
                <p>Please review the engagement terms carefully. Once you're ready to proceed, you can sign the engagement letter through your client portal.</p>
                <a href="{{ url('/client/engagement') }}" class="cta-button">View & Sign Engagement Letter</a>
            </div>

            <p><strong>Important Information:</strong></p>
            <p>This engagement letter details our mutual responsibilities and the scope of services we'll provide. Please ensure you understand all terms before signing.</p>

            <p>If you have any questions or need clarification on any aspect of the engagement letter, please don't hesitate to contact us.</p>

            <p>We look forward to working with you on your self assessment tax matters.</p>

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