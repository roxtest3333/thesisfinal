{{-- resources\views\pdf\request-form.blade.php--}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PRMSU Document Request Form</title>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
        }
        .header { 
            text-align: center; 
            margin-bottom: 5mm;
        }
        .university-name {
            font-weight: bold;
            font-size: 12pt;
        }
        .form-title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin: 8mm 0;
            font-size: 13pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5mm;
        }
        table.bordered, table.bordered th, table.bordered td {
            border: 1px solid #000;
        }
        th, td {
            padding: 2mm;
            vertical-align: top;
        }
        .section-title {
            font-weight: bold;
            margin: 4mm 0 2mm 0;
            font-size: 11pt;
        }
        .checkbox {
            display: inline-block;
            width: 4mm;
            height: 4mm;
            border: 1px solid #000;
            margin-right: 1mm;
            text-align: center;
            line-height: 4mm;
            font-size: 9pt;
        }
        .checked:after {
            content: "✓";
        }
        .underline {
            display: inline-block;
            border-bottom: 1px solid #000;
            min-width: 50mm;
            padding-bottom: 1mm;
            margin: 0 2mm;
        }
        .signature-line {
            display: inline-block;
            width: 60mm;
            border-top: 1px solid #000;
            margin-top: 15mm;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .small-text {
            font-size: 9pt;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="university-name">Republic of the Philippines</div>
        <div class="university-name">PRESIDENT RAMON MAGSAYSAY STATE UNIVERSITY</div>
        <div>(Formerly Ramon Magsaysay Technological University)</div>
        <div>Castillejos | San Marcelino | Botolan | Iba | Masinloc | Candelaria | Sta. Cruz</div>
    </div>

    <div class="form-title">DOCUMENT REQUEST FORM</div>
    
    <div class="small-text text-center">Fill out this form completely and accurately. PLEASE PRINT ALL ENTRIES</div>

    <!-- Client Information -->
    <div class="section-title">CLIENT'S INFORMATION (Record's Owner)</div>
    <table class="bordered">
        <tr>
            <td width="25%">First Name: <span class="underline">{{ $student->first_name ?? '' }}</span></td>
            <td width="25%">Middle Name: <span class="underline">{{ $student->middle_name ?? '' }}</span></td>
            <td width="25%">Last Name: <span class="underline">{{ $student->last_name ?? '' }}</span></td>
            <td width="25%">Extension Name: <span class="underline">{{ $requestData['extension_name'] ?? '' }}</span></td>
        </tr>
        <tr>
            <td colspan="2">
                Sex: 
                <span class="checkbox @if(($student->sex ?? '') === 'Male') checked @endif"> </span> Male
                <span class="checkbox @if(($student->sex ?? '') === 'Female') checked @endif"> </span> Female
                Birthday: <span class="underline">{{ isset($student->birthday) ? date('m/d/Y', strtotime($student->birthday)) : '' }}</span>
            </td>
            <td colspan="2">
                Birthplace: <span class="underline">{{ $student->birthplace ?? '' }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                Did you have a change or correction of name at PRMSU?
                <span class="checkbox @if(($requestData['name_changed'] ?? '') === 'No') checked @endif"> </span> No
                <span class="checkbox @if(($requestData['name_changed'] ?? '') === 'Yes') checked @endif"> </span> Yes, 
                my original name was: <span class="underline">{{ $requestData['original_name'] ?? '' }}</span>
            </td>
        </tr>
    </table>

    <!-- Academic Information -->
    <div class="section-title">ACADEMIC INFORMATION</div>
    <table class="bordered">
        <tr>
            <td width="50%">Student number: <span class="underline">{{ $student->student_id ?? '' }}</span></td>
            <td width="50%">Latest Course Enrolled at PRMSU: <span class="underline">{{ $student->course ?? '' }}</span></td>
        </tr>
        <tr>
            <td colspan="2">
                Did you graduate from PRMSU?
                <span class="checkbox @if(($requestData['graduated'] ?? '') === 'Yes') checked @endif"> </span> Yes, I graduated on 
                <span class="underline">{{ $requestData['graduation_date'] ?? '' }}</span>
                <span class="checkbox @if(($requestData['graduated'] ?? '') === 'No') checked @endif"> </span> No, I did not graduate from PRMSU. 
                My last term of attendance was 
                <span class="checkbox @if(($requestData['last_term'] ?? '') === '1st') checked @endif"> </span> 1st term
                <span class="checkbox @if(($requestData['last_term'] ?? '') === '2nd') checked @endif"> </span> 2nd term
                <span class="checkbox @if(($requestData['last_term'] ?? '') === '3rd') checked @endif"> </span> 3rd term
                <span class="checkbox @if(($requestData['last_term'] ?? '') === 'Summer') checked @endif"> </span> Summer/Midyear of School Year 
                <span class="underline">{{ $requestData['last_sy'] ?? '' }}</span>
            </td>
        </tr>
    </table>

    <!-- Contact Information -->
    <div class="section-title">CONTACT INFORMATION</div>
    <table class="bordered">
        <tr>
            <td width="50%">Tel./mobile number: <span class="underline">{{ $student->contact_number ?? '' }}</span></td>
            <td width="50%">Email address: <span class="underline">{{ $student->email ?? '' }}</span></td>
        </tr>
        <tr>
            <td colspan="2">Home/Mailing address: <span class="underline">{{ $student->home_address ?? '' }}</span></td>
        </tr>
    </table>

    <!-- Document Request Details -->
    <div class="section-title">DETAILS OF DOCUMENT REQUEST | Date Requested: <span class="underline">{{ date('m/d/Y') }}</span></div>
    <div class="small-text">Please refer to the following list to identify the type/s of document/s being requested.</div>
    <div class="small-text">Write the name/type of document to request if it is not included in the list.</div>
    
    <table class="bordered" style="margin-top: 3mm;">
        <tr>
            <td width="50%">
                <strong>TOR</strong><br>
                <strong>Certifications</strong><br>
                <span class="checkbox @if(in_array('Original Diploma', $requestData['documents'] ?? [])) checked @endif"> </span> Original Diploma<br>
                <span class="checkbox @if(in_array('Units Earned', $requestData['documents'] ?? [])) checked @endif"> </span> Units Earned<br>
                <span class="checkbox @if(in_array('Copy of Diploma', $requestData['documents'] ?? [])) checked @endif"> </span> Copy of Diploma<br>
                <span class="checkbox @if(in_array('Grades (per semester/term)', $requestData['documents'] ?? [])) checked @endif"> </span> Grades (per semester/term)<br>
                <span class="checkbox @if(in_array('Form 137', $requestData['documents'] ?? [])) checked @endif"> </span> Form 137<br>
                <span class="checkbox @if(in_array('Grades (all terms attended)', $requestData['documents'] ?? [])) checked @endif"> </span> Grades (all terms attended)<br>
                <span class="checkbox @if(in_array('RLE - Related Learning Experience', $requestData['documents'] ?? [])) checked @endif"> </span> RLE - Related Learning Experience<br>
                <span class="checkbox @if(in_array('Academic Completion', $requestData['documents'] ?? [])) checked @endif"> </span> Academic Completion<br>
            </td>
            <td width="50%">
                <span class="checkbox @if(in_array('CAV - Certification/Authentication/Verification', $requestData['documents'] ?? [])) checked @endif"> </span> CAV - Certification/Authentication/Verification<br>
                <span class="checkbox @if(in_array('Graduation', $requestData['documents'] ?? [])) checked @endif"> </span> Graduation<br>
                <span class="checkbox @if(in_array('As a Candidate for Graduation', $requestData['documents'] ?? [])) checked @endif"> </span> As a Candidate for Graduation<br>
                <span class="checkbox @if(in_array('As Honor Graduate', $requestData['documents'] ?? [])) checked @endif"> </span> As Honor Graduate<br>
                <span class="checkbox @if(in_array('Subjects Enrolled/Curriculum', $requestData['documents'] ?? [])) checked @endif"> </span> Subjects Enrolled/Curriculum<br>
                <span class="checkbox @if(in_array('Enrollment/Registration', $requestData['documents'] ?? [])) checked @endif"> </span> Enrollment/Registration<br>
                <span class="checkbox @if(in_array('English as Medium of Instruction', $requestData['documents'] ?? [])) checked @endif"> </span> English as Medium of Instruction<br>
                <span class="checkbox @if(in_array('Course Description', $requestData['documents'] ?? [])) checked @endif"> </span> Course Description (max 5 per certification)<br>
            </td>
        </tr>
    </table>

    <!-- Document Request Table -->
    <table class="bordered" style="margin-top: 5mm;">
        <thead>
            <tr>
                <th width="40%">Document Type</th>
                <th width="30%">Purpose</th>
                <th width="30%">No. of Copies</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requestData['documents'] ?? [] as $document)
            <tr>
                <td>{{ $document['type'] }}</td>
                <td>{{ $document['purpose'] ?? 'N/A' }}</td>
                <td>{{ $document['copies'] ?? 1 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Request Status -->
    <div style="margin-top: 5mm;">
        <span class="checkbox @if(($requestData['status'] ?? '') === 'Processing') checked @endif"> </span> Request is acknowledged for processing
        <span class="checkbox @if(($requestData['status'] ?? '') === 'On Hold') checked @endif"> </span> Request is put on hold
        <span class="checkbox @if(($requestData['status'] ?? '') === 'Denied') checked @endif"> </span> Request is denied
        <span class="small-text">(Note: CRO staff must keep a copy of this form if the client's request is put on hold or denied.)</span>
    </div>

    <!-- Remarks -->
    <div style="margin-top: 5mm;">
        <div class="section-title">Remarks:</div>
        <div class="underline" style="min-height: 10mm; width: 100%;">{{ $requestData['remarks'] ?? '' }}</div>
    </div>

    <!-- For Staff Use -->
    <table style="margin-top: 5mm; width: 100%;">
        <tr>
            <td width="50%">
                For submission by the client:<br>
                <div class="underline" style="min-height: 10mm; width: 90%;"></div>
            </td>
            <td width="50%">
                For the signature of the client once the document/s requested is/are received:<br>
                <div class="underline" style="min-height: 10mm; width: 90%;"></div>
            </td>
        </tr>
        <tr>
            <td>
                Request received by (name & signature of receiving staff):<br>
                <div class="underline" style="min-height: 10mm; width: 90%;"></div>
            </td>
            <td>
                Date of issuance of requested document/s: <span class="underline"></span>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="small-text" style="margin-top: 3mm;">
        Code: PRMSU-AA-QURSF11 | Revision No.: 00 | Effectivity Date: July 15, 2024
    </div>

    <!-- Page Break for Second Page -->
    <div style="page-break-after: always;"></div>

    <!-- Second Page - Delivery Instructions -->
    <div class="header">
        <div class="university-name">Republic of the Philippines</div>
        <div class="university-name">PRESIDENT RAMON MAGSAYSAY STATE UNIVERSITY</div>
    </div>

    <div class="form-title">CLAIMS / DELIVERY INSTRUCTIONS</div>
    <div class="small-text text-center">Please select and mark your preferred mode of issuance/release of the requested document/s.</div>

    <!-- Pickup Option -->
    <div style="margin-top: 5mm;">
        <div class="section-title">
            <span class="checkbox @if(($requestData['delivery_method'] ?? '') === 'Pickup') checked @endif"> </span> PICK UP.
        </div>
        <div class="small-text" style="margin-left: 8mm;">
            The document/s will be claimed by the owner who, upon claiming, will present one (1) valid ID and the Official Receipt of payment.
        </div>
    </div>

    <!-- Proxy Option -->
    <div style="margin-top: 5mm;">
        <div class="section-title">
            <span class="checkbox @if(($requestData['delivery_method'] ?? '') === 'Proxy') checked @endif"> </span> PROXY.
        </div>
        <div class="small-text" style="margin-left: 8mm;">
            A proxy/representative will be sent to claim the document/s. Upon claiming, he/she will bring the authorization letter from the record's owner, a photocopy of his/her valid ID (original ID to be presented to the processing staff), and one (1) photocopy of the valid ID of the owner and the Official Receipt of payment.
        </div>
        <table style="margin-left: 8mm; margin-top: 2mm;">
            <tr>
                <td width="50%">Name of Authorized Representative: <span class="underline">{{ $requestData['proxy_name'] ?? '' }}</span></td>
                <td width="50%">Contact number of representative: <span class="underline">{{ $requestData['proxy_contact'] ?? '' }}</span></td>
            </tr>
        </table>
    </div>

    <!-- Courier Option -->
    <div style="margin-top: 5mm;">
        <div class="section-title">
            <span class="checkbox @if(($requestData['delivery_method'] ?? '') === 'Courier') checked @endif"> </span> COURIER.
        </div>
        <div class="small-text" style="margin-left: 8mm;">
            Please send the document/s via courier to the address indicated in the form. It is understood that the delivery period is over and above the processing period. (Please read the following policies adopted by the University for the delivery of requested documents/s via courier service.)
        </div>
        
        <div style="margin-left: 8mm; margin-top: 2mm;">
            <div><strong>Complete Mailing Address:</strong></div>
            <div class="underline" style="min-height: 10mm; width: 100%;">{{ $requestData['mailing_address'] ?? '' }}</div>
            
            <div style="margin-top: 2mm;"><strong>Preferred courier service (if any):</strong></div>
            <div class="underline" style="min-height: 5mm; width: 100%;">{{ $requestData['courier_preference'] ?? '' }}</div>
        </div>
    </div>

    <!-- Payment Information -->
    <div style="margin-top: 5mm;">
        <div class="section-title">Payment includes fees for the following:</div>
        <table class="bordered" style="width: 100%;">
            <tr>
                <td width="40%"><strong>Processing Fee</strong></td>
                <td width="30%"><strong>Courier Service Fee</strong></td>
                <td width="30%"><strong>Convenience Fee</strong></td>
            </tr>
            <tr>
                <td>Initial payment for TOR, amounting to Php 150.00 will be paid by the client before the requested document is processed by the Office.</td>
                <td>Standard delivery rate of courier service will be applied.</td>
                <td>Php 50.00</td>
            </tr>
        </table>
    </div>

    <!-- Conditions & Reminders -->
    <div style="margin-top: 5mm;">
        <div class="section-title">CONDITIONS & REMINDERS</div>
        <ol class="small-text" style="padding-left: 6mm;">
            <li>Under existing laws, only the owner of this records is allowed to request documents in connection with his/her school records and claim the requested documents.</li>
            <li>The University reserves the right to withhold, deny or cancel any request for document/s due to incomplete requirements and/or pending accountabilities of the student.</li>
            <li>To verify the identity of the requesting/claiming party, one (1) valid identification card shall be required for presentation upon request and one (1) valid identification card upon claiming the document/s.</li>
            <li>Requests and claiming of documents by representative/proxy should be covered by an accomplished Proxy Request of Records Form or an Authorization Letter from the record's owner. The proxy/representative must present his/her valid ID and one valid ID of the owner during said transactions.</li>
            <li>Please return this form to the Office of the University or Campus Registrar after payment at the Accounting Office (if payment is applicable). Without this form, the request cannot be processed.</li>
            <li>Documents not claimed after two (2) years will be destroyed.</li>
        </ol>
    </div>

    <!-- Conforme Section -->
    <div style="margin-top: 10mm;">
        <div class="section-title">CONFORMÉ</div>
        <div class="small-text">
            I have read and understood all the conditions and reminders in connection with this request. I likewise agree to comply with them. I hereby certify the correctness of all entries. Any false information I supplied shall render me liable for the consequences of my wrong actions.
        </div>
        
        <div style="margin-top: 10mm;">
            <div class="text-center">
                <div class="signature-line"></div>
                <div>Signature over Printed Name of Client</div>
                <div>Date: <span class="underline">{{ date('m/d/Y') }}</span></div>
            </div>
        </div>
    </div>

    <!-- Data Subject Consent -->
    <div style="margin-top: 15mm;">
        <div class="section-title">DATA SUBJECT CONSENT</div>
        <div class="small-text">
            This is to certify, that I <span class="underline">{{ $student->first_name ?? '' }} {{ $student->middle_name ?? '' }} {{ $student->last_name ?? '' }}</span> have given my permission to the
            <span class="underline">PRMSU - Office of the University & Campus Registrars' Offices</span> in the collection, lawful use, and disclosure of my personal information (including sensitive and privileged information, if may be applicable), which may or may not include all information contained in the forms and documentations I have submitted in line with the preparation and issuance of my requested documents.
            <br><br>
            This is also to certify that I have permitted the PRMSU - Office of the Campus & Registrars' Offices and other appropriate offices in the University to provide the above-cited information to legitimate offices/institutions requesting such information in relation to the performance of their legitimate/lawfully-mandated functions.
            <br><br>
            This further permits the PRMSU - Office of the Campus & Registrars' Offices to process my information to the maximum extent allowed by law, to pursue its objectives as an educational institution. This may include a variety of academic, administrative, research, historical, and statistical purposes.
            <br><br>
            I am assured that the security systems of the PRMSU - Office of the Campus & Registrars' Offices are in place to protect and safeguard my personal information.
            <br><br>
            I understand that the PRMSU - Office of the Campus & Registrars' Offices are authorized to process my personal and sensitive personal information without the need for my consent under the relevant portions of Sections 4 (Scope), 12 (Criteria for Lawful Processing of Information) and 13 (Sensitive Personal Information & Privileged Information) of the Philippine Data Privacy Act.
            <br><br>
            This consent allows the PRMSU - Office of the Campus & Registrars' Offices to comply with R.A. 10179, also known as the Data Privacy Act of 2012.
        </div>
        
        <div style="margin-top: 10mm;">
            <div class="text-center">
                <div class="signature-line"></div>
                <div>Signature over Printed Name of Client</div>
                <div>Date: <span class="underline">{{ date('m/d/Y') }}</span></div>
            </div>
        </div>
    </div>

    <!-- Office Information -->
    <div style="margin-top: 15mm;" class="small-text">
        <div class="text-center">
            <strong>OFFICE OF THE UNIVERSITY ADMISSION AND REGISTRATION SERVICES</strong><br>
            Facebook Page: Student Admissions and Registration Services - PRMSU<br>
            Email: university@prmsu.org<br>
            Website: www.prmsu.edu.ph<br>
            Tel/Fax No.: (047) 626 2103 loc. 139
        </div>
        
        <div style="margin-top: 5mm;">
            <strong>Office of the Campus Registrar</strong><br>
            Campus: <span class="underline">{{ $requestData['campus'] ?? '' }}</span><br>
            Contact number: <span class="underline">{{ $requestData['campus_contact'] ?? '' }}</span><br>
            Email Address: <span class="underline">{{ $requestData['campus_email'] ?? '' }}</span>
        </div>
    </div>

    <!-- Footer -->
    <div class="small-text" style="margin-top: 3mm;">
        Code: PRMSU-AA-QURSF11 | Revision No.: 00 | Effectivity Date: July 15, 2024
    </div>
</body>
</html>