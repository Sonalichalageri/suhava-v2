<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('display_errors', 1);
class Admin extends CI_Controller {
    
     public function __construct() {
        parent::__construct();
        
        if($this->session->userdata("user_id")=="")
		{
			redirect(base_url()."login/index?FailedLogin");						
		}
		
        $this->load->model('Donar_model');
        $this->load->library('email'); // Load the email library
    }
    
	public function index()
	{
	    
	    $this->load->view('admin/header');
	    $this->load->view('admin/dashboard');
	    $this->load->view('admin/footer');
	}
	
	public function donor()
	{
	    $data['pending_donors'] = $this->Donar_model->get_pending_donors();
	    $this->load->view('admin/header');
	    $this->load->view('admin/donorlist',$data);
	    $this->load->view('admin/footer');
		
	}
	public function verify_donor() {
        $donor_id = $this->input->post('donor_id');
        $result = $this->Donar_model->verify_donor($donor_id);
        $email = $this->Donar_model->get_email($donor_id);
        $this->sendReceipt($donor_id);
        if($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
    
    public function pdf(){
        $this->load->library('Pdf');
        $htmlContent = $this->load->view('invoice_template', [], true);
        $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
      
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Company');
        $pdf->SetTitle('Invoice');
        $pdf->SetSubject('Invoice Document');
        $pdf->SetKeywords('TCPDF, PDF, invoice, test, guide');

        // Add a page
        $pdf->AddPage();

        // Convert HTML to PDF
        $pdf->writeHTML($htmlContent, true, false, true, false, '');

        // Close and output PDF document
        $pdfFilePath = FCPATH . 'uploads/invoice.pdf';
        $pdf->Output($pdfFilePath, 'I'); // 'F' for saving to a file
    }
    
    public function receipt()
	{
	    
	   // $this->load->view('admin/receipt');
	    
	}
	
	public function verify_list()
	{
	    $data['pending_donors'] = $this->Donar_model->verified_list();
	    $this->load->view('admin/header');
	    $this->load->view('admin/donor_verify_list',$data);
	    $this->load->view('admin/footer');
		
	}
	
	public function generate_receipt()
	{
	    
	    $data["url"] = $url = $this->uri->segment(3);
	    $donor_base64_decode = base64_decode($url,true);
        $donor_json_decode = json_decode($donor_base64_decode,true);
        $donor_id = $donor_json_decode["donor_id"];
        
	    $data['donor_data'] = $donor_data = $this->Donar_model->get_donor($donor_id);
	    $this->load->view('admin/receipt',$data);
	}
	
	public function view_donor()
	{
	    
	    $data["url"] = $url = $this->uri->segment(3);
	    $donor_base64_decode = base64_decode($url,true);
        $donor_json_decode = json_decode($donor_base64_decode,true);
        $donor_id = $donor_json_decode["donor_id"];
        
	    $data['donor_data'] = $donor_data = $this->Donar_model->get_donor($donor_id);
	   // print_r($donor_data);
	   // die("debug 118");
	    
	    $this->load->view('admin/header');
	    $this->load->view('admin/view_donor',$data);
	    $this->load->view('admin/footer');
	}
 public function sendReceipt($donor_id) {
        $data = $donor_data = $this->Donar_model->get_donor($donor_id);
        $this->load->library('Pdf');
        $donor_name = $data['DonorName'];
        $mobile_number = $data['ContactNumber'];
        $amount = $data['DonationAmount'];
        $gaushala_details = 'BHARTIYA GOVANSH RAKSHAN SAMVARDHAN PARISHAD';
        $donation_date = $data['DonationDate'];

        // Generate HTML Content for Donation Receipt
        $htmlContent = '<html><head><title>Donation Receipt</title></head><body>';
        $htmlContent .= '<h1>Donation Receipt #' .'GP00000'. $donor_id . '</h1>';
        $htmlContent .= '<p>Thank you, ' . $donor_name . ', for your generous donation!</p>';
        $htmlContent .= '<p>Donation Details:</p>';
        $htmlContent .= '<ul>';
        $htmlContent .= '<li><strong>Donor Name:</strong> ' . $donor_name . '</li>';
        $htmlContent .= '<li><strong>Mobile Number:</strong> ' . $mobile_number . '</li>';
        $htmlContent .= '<li><strong>Amount Donated:</strong> ' . $amount . '</li>';
        $htmlContent .= '<li><strong>Donation Date:</strong> ' . $donation_date . '</li>';
        $htmlContent .= '<li><strong>Gaushala Details:</strong> ' . $gaushala_details . '</li>';
        $htmlContent .= '</ul>';
        $htmlContent .= '<p>We appreciate your support in our mission to care for the cows. Your contribution helps us provide better facilities and care for them.</p>';
        $htmlContent .= '<p>Thank you once again!</p>';
        $htmlContent .= '</body></html>';

        // Generate PDF using TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);
        $pdf->writeHTML($htmlContent, true, false, true, false, '');
        
        // Define upload path
        $uploadPath = FCPATH . 'uploads/recipts/';
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $fileName = 'receipt_' . $donor_id . '.pdf';
        $fullPath = $uploadPath . $fileName;
        $pdf->Output($fullPath, 'F'); // Save the PDF to the server

        // Email configuration
        

        // Attach the file
        $this->email->attach($fullPath);

        // Set email content
       $this->email->from('prajapatiaws@gmail.com', 'Shree Gopal');
        $this->email->to('prajapatiarvind1007@gmail.com');
        $this->email->subject('Verification Successful');
        $this->email->message('Please find your receipt attached.');

        // Send email
        if ($this->email->send()) {
            echo 'Email sent successfully';

            // Update database to mark the bill as sent
            $data = array('bill_sent' => '1');
            #$this->order_bills->update($order_id, $data);
        } else {
            echo 'Failed to send email';
            echo $this->email->print_debugger();
        }

        // Optional: Delete the uploaded file after sending the email
        // unlink($fullPath);
    }


}
