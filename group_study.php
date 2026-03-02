<? include("includes/header.php");?>
<style>
/* --------- Layout & Typography --------- */
.section-title{
  text-align:center;
  font-size:28px;
  font-weight:700;
  margin:40px 0 25px;
}
.section-title span{
  color:#dd3230;
}
.section-divider{
  border:0;
  border-top:2px dashed #de3331;
  margin:25px 0 35px;
}
.panel.study-card{
  border-radius:6px;
  border:1px solid #e6e6e6;
  transition:all .2s ease-in-out;
  box-shadow:0 1px 1px rgba(0,0,0,.04);
}
.panel.study-card .panel-heading{
  background:transparent;
  border-bottom:1px solid #eee;
  border-top-left-radius:6px;
  border-top-right-radius:6px;
  padding:10px 10px;
}
.panel.study-card .panel-title{
  font-weight:700;
  line-height:1.3;
}
.panel.study-card .panel-title .sub{
  display:block;
  font-weight:600;
  font-size:12px;
  color:#777;
  margin-top:2px;
  letter-spacing:.3px;
  text-transform:uppercase;
}
.panel.study-card:hover{
  transform:translateY(-3px);
  box-shadow:0 8px 18px rgba(33,150,243,.18);
  border-color:#d0e7ff;
}
.list-group.study-links{
  margin:0;
}
.list-group.study-links>li{
  border-color:transparent;
  padding:0px;
}
.study-link{
  display:flex;
  align-items:center;
  color:#333;
  font-weight:600;
  text-decoration:none !important;
}
.study-link img{
  width:30px;
  height:30px;
  margin-right:8px;
}
.study-link .cta{
  margin-left:auto;
  font-weight:700;
  color:#1976d2;
  transition:transform .2s ease;
}
.study-link:hover .cta{ transform:translateX(3px); }

/* Utilities & spacing fixes */
.mt-0{margin-top:0!important}
.mb-0{margin-bottom:0!important}
.mb-10{margin-bottom:10px}
.mb-20{margin-bottom:20px}
.pt-10{padding-top:10px}
.pb-0{padding-bottom:0!important}

/* Banner */
.study-banner{margin-top:-1px;width:100%;height:auto;display:block}

/* Make columns tighten nicely on small screens */
@media (max-width:991px){
  .panel.study-card{min-height:auto;
      margin-bottom:10px;
  }
}


.top_flex {
    display: flex;
    align-items: center;
    gap: 16px;
}

.top_flex img {
       width: 65px;
    height: 65px;
    object-fit: contain;
}

.main_flex1 {
    display: flex;
    width: 100%;
    justify-content: space-between;
    align-items: center;
}

.study-links {
    display: flex;
    gap: 5px;
    margin-right: 3px !important;
}

@media(max-width:767px)
{
    .study-link img {
    width: 20px;
    height: 20px;
    margin-right: 8px;
}
.top_flex img {
    width: 45px;
    
}
.top_flex {
    display: flex;
    align-items: center;
    gap: 8px;
}
}
</style>

<a href="http://cciestudygroups.com/" target="_blank" rel="noopener"><img class="study-banner" src="images/bannerr.jpg" alt="Study Groups Banner"></a>

<div class="container">
  <!-- CCIE -->
  <h2 class="section-title"><span>CISCO</span> Lab Study Group</h2>
  <div class="row">
    <!-- Card -->
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
            <div class=""><img src="images/ccie_logo.png" /></div>
          <h3 class="panel-title">CCIE EI v1.1 <span class="sub">Lab Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/chinesedumps/2" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://chat.whatsapp.com/Lh8t0dZhQsS3dFIk7WBRn5" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
          
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- Repeat for other CCIE cards -->
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
            <div class=""><img src="images/ccie_logo.png" /></div>
          <h3 class="panel-title">CCIE SP v5.1 <span class="sub">Lab Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/chinesedumps/8" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">
            </a>
          </li>
         
         <li class="list-group-item">
            <a class="study-link"  target="_blank" rel="noopener"  href="https://chat.whatsapp.com/Ef9lwSu3UEy2FsjZ8t6oYs" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp"> 
            </a>
          </li>
          
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
            <div class=""><img src="images/ccie_logo.png" /></div>
          <h3 class="panel-title">CCIE Security v6.1 <span class="sub">Lab Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/chinesedumps/3" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">
            </a>
          </li>
         <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/G9YpRzFXBvq2OW9dcB04mN" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp"> 
            </a>
          </li>
          
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
          
          
        </ul>
      </div>
    </div>

    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
            <div class=""><img src="images/ccie_logo.png" /></div>
          <h3 class="panel-title">CCIE Data Center v3.0 <span class="sub">Lab Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/chinesedumps/5" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">
            </a>
          </li>
         
           <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/IjVbWu5dKtM6Xxvu4Z3bIQ" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp"> 
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
          
        </ul>
      </div>
    </div>

    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
             <div class=""><img src="images/ccie_logo.png" /></div>
          <h3 class="panel-title">CCIE Collab v3.1 <span class="sub">Lab Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/chinesedumps/4" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">
            </a>
          </li>
          <li class="list-group-item">
            <a class="study-link" href="" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp"> 
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
             <div class=""><img src="images/ccie_logo.png" /></div>
          <h3 class="panel-title">CCIE Cloud <span class="sub">Lab Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/cciecollabv3" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
           <li class="list-group-item">
            <a class="study-link" href="" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp"> 
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
          
        </ul>
      </div>
    </div>

    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
             <div class=""><img src="images/ccie_logo.png" /></div>
          <h3 class="panel-title">CCIE Wireless v1.0 <span class="sub">Lab Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/chinesedumps/6" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">
            </a>
          </li>
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/KOVrO1MZhgU8k5K7lcfHOz" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp"> 
            </a>
          </li>
          
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
          
          
        </ul>
      </div>
    </div>

    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
             <div class=""><img src="images/ccie_logo.png" /></div>
          <h3 class="panel-title">CCDE <span class="sub">Lab Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/chinesedumps/9" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
           <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/JaWtqLYyuPiARPq1I727Yp" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp"> 
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
          
          
        </ul>
      </div>
    </div>
  
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
             <div class=""><img src="images/images (9)_11zon.jpg" /></div>
          <h3 class="panel-title">CCNP EI <span class="sub">Study Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/chinesedumps/17" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://chat.whatsapp.com/BCuGOfg8Xje7tXmBviy17A" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- CCNP Security -->
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
            <div class=""><img src="images/images (9)_11zon.jpg" /></div>
          <h3 class="panel-title">CCNP Security <span class="sub">Study Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/chinesedumps/16" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://chat.whatsapp.com/Dg4Z19QYlJmCFr30jGjmsE" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- CCNP DC -->
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
            <div class=""><img src="images/images (9)_11zon.jpg" /></div>
          <h3 class="panel-title">CCNP Data Center <span class="sub">Study Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/chinesedumps/18" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">
            </a>
          </li>
          
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://chat.whatsapp.com/LxTXyFlBwm4883YKy8YS6R" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- CCNP Collaboration -->
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
            <div class=""><img src="images/images (9)_11zon.jpg" /></div>
          <h3 class="panel-title">CCNP Collaboration <span class="sub">Study Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/ccnpcollab" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://chat.whatsapp.com/GXPRO05XAchKI3qpY7cRjO" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp"> 
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- CCNP SP -->
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
            <div class=""><img src="images/images (9)_11zon.jpg" /></div>
          <h3 class="panel-title">CCNP SP <span class="sub">Study Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/ccnpsp" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://chat.whatsapp.com/H5vIC2jmI4N7i8OhidYgNk" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp"> 
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- CCNP DevNet -->
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
            <div class=""><img src="images/images (9)_11zon.jpg" /></div>
          <h3 class="panel-title">CCNP DevNet <span class="sub">Study Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/ccnpdevnet" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">
            </a>
          </li>
        
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://chat.whatsapp.com/CUMnznfZvqKGjuuKpWotFv" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp"> 
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!-- CCNP Wireless -->
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
            <div class=""><img src="images/images (9)_11zon.jpg" /></div>
          <h3 class="panel-title">CCNP Wireless v1.0 <span class="sub">Lab Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://t.me/cciewirelessv1" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/CcQ1EAQJwx49ZRLLy0W1hA" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
 
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex">
            <div class=""><img src="images/ccna_600_11zon.jpg" /></div>
          <h3 class="panel-title"> CCNA <span class="sub">Study Group</span></h3>
        </div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/15" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">
            </a>
          </li>
          
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/EWAOXm6fSQD5Nta7lta0di" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!--<div class="col-sm-6 col-md-4">-->
    <!--  <div class="panel study-card main_flex1">-->
    <!--    <div class="panel-heading top_flex">-->
    <!--        <div class=""><img src="images/ccie_logo.png" /></div>-->
    <!--      <h3 class="panel-title">210-250 SECFND <span class="sub">Study Group</span></h3>-->
    <!--    </div>-->
    <!--    <ul class="list-group study-links">-->
    <!--      <li class="list-group-item">-->
    <!--        <a class="study-link" href="#" target="_blank" rel="noopener" title="Telegram">-->
    <!--          <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">-->
    <!--        </a>-->
    <!--      </li>-->
          
    <!--      <li class="list-group-item">-->
    <!--        <a class="study-link" href="https://chat.whatsapp.com/Hgsir4kNBL1Issbdv02Fsd" target="_blank" rel="noopener" title="WhatsApp">-->
    <!--          <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp"> -->
    <!--        </a>-->
    <!--      </li>-->
    <!--       <li class="list-group-item">-->
    <!--        <a class="study-link" target="_blank" rel="noopener" href="#" title="Microsoft Teams">-->
    <!--          <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">-->
    <!--        </a>-->
    <!--      </li>-->
    <!--    </ul>-->
    <!--  </div>-->
    <!--</div>-->

    <!--<div class="col-sm-6 col-md-4">-->
    <!--  <div class="panel study-card main_flex1">-->
    <!--    <div class="panel-heading top_flex">-->
    <!--        <div class=""><img src="images/ccie_logo.png" /></div>-->
    <!--      <h3 class="panel-title">210-255 SECOPS <span class="sub">Study Group</span></h3>-->
    <!--    </div>-->
    <!--    <ul class="list-group study-links">-->
    <!--      <li class="list-group-item">-->
    <!--        <a class="study-link" href="#" target="_blank" rel="noopener" title="Telegram">-->
    <!--          <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">-->
    <!--        </a>-->
    <!--      </li>-->
         
    <!--      <li class="list-group-item">-->
    <!--        <a class="study-link" href="https://chat.whatsapp.com/KrvpfiLvRvZG7QvftCSW0m" target="_blank" rel="noopener" title="WhatsApp">-->
    <!--          <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">-->
    <!--        </a>-->
    <!--      </li>-->
    <!--       <li class="list-group-item">-->
    <!--        <a class="study-link" target="_blank" rel="noopener" href="#" title="Microsoft Teams">-->
    <!--          <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">-->
    <!--        </a>-->
    <!--      </li>-->
    <!--    </ul>-->
    <!--  </div>-->
    <!--</div>-->

    <!--<div class="col-sm-6 col-md-4">-->
    <!--  <div class="panel study-card main_flex1">-->
    <!--    <div class="panel-heading top_flex">-->
    <!--        <div class=""><img src="images/ccie_logo.png" /></div>-->
    <!--      <h3 class="panel-title">200-901 DEVASC <span class="sub">Study Group</span></h3>-->
    <!--    </div>-->
    <!--    <ul class="list-group study-links">-->
    <!--      <li class="list-group-item">-->
    <!--        <a class="study-link" href="https://t.me/chinesedumps/7" target="_blank" rel="noopener" title="Telegram">-->
    <!--          <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram">-->
    <!--        </a>-->
    <!--      </li>-->
         
    <!--      <li class="list-group-item">-->
    <!--        <a class="study-link" href="https://chat.whatsapp.com/KeApIQETOHI9AVqFblLNL5" target="_blank" rel="noopener" title="WhatsApp">-->
    <!--          <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">-->
    <!--        </a>-->
    <!--      </li>-->
    <!--       <li class="list-group-item">-->
    <!--        <a class="study-link" target="_blank" rel="noopener" href="#" title="Microsoft Teams">-->
    <!--          <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">-->
    <!--        </a>-->
    <!--      </li>-->
    <!--    </ul>-->
    <!--  </div>-->
    <!--</div>-->
  </div>

  <hr class="section-divider">

  <!-- Other Courses -->
  <h2 class="section-title"><span>Other</span> Courses Group</h2>
  <div class="row">
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (10)_11zon.jpg" /></div>
        <h3 class="panel-title">PMP <span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/950" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/LVAM8WG3xrRJzKkRO2NgKS" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp"> 
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/AWS_resized.png" /></div>
        <h3 class="panel-title">AWS <span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/CmW9cOKHdRHLuy9Bp9dLyw" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>

    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/Juniper (1)_11zon.jpg" /></div>
        <h3 class="panel-title">Juniper <span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
         <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/12" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/C75yObpstvgJDSzQdhS5MK" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp"> 
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>

    <!--<div class="col-sm-6 col-md-4">-->
    <!--  <div class="panel study-card main_flex1">-->
    <!--    <div class="panel-heading top_flex"> <div class=""><img src="images/ccie_logo.png" /></div>-->
    <!--    <h3 class="panel-title">Proxy Exams <span class="sub">Study Group</span></h3></div>-->
    <!--    <ul class="list-group study-links">-->
    <!--       <li class="list-group-item">-->
    <!--        <a class="study-link" href="" target="_blank" rel="noopener" title="Telegram">-->
    <!--          <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> -->
    <!--        </a>-->
    <!--      </li>-->
    <!--      <li class="list-group-item">-->
    <!--        <a class="study-link" href="https://chat.whatsapp.com/Fcdto1KBtg4EgjrtYoAalf" target="_blank" rel="noopener" title="WhatsApp">-->
    <!--          <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp"> -->
    <!--        </a>-->
    <!--      </li>-->
    <!--       <li class="list-group-item">-->
    <!--        <a class="study-link" target="_blank" rel="noopener" href="#" title="Microsoft Teams">-->
    <!--          <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">-->
    <!--        </a>-->
    <!--      </li>-->
    <!--    </ul>-->
    <!--  </div>-->
    <!--</div>-->

    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (5)_11zon.jpg" /></div>
        <h3 class="panel-title">CISSP <span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/14" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/J0beg6QV4XSDvNRSu0rL8b" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/blob_11zon.jpg" /></div>
        <h3 class="panel-title">CompTIA <span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/14" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/IAau3UhPNhU67Sjs8Br0a1" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/Palo Alto Log_11zon.jpg" /></div>
        <h3 class="panel-title">Palo Alto <span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/10" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/LcUqfXr5uef12Owu070OUi" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/Checkpoint_11zon.jpg" /></div>
        <h3 class="panel-title">Checkpoint <span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/By9FTF80lGt4KsqoU6MLnJ" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (8)_11zon.jpg" /></div>
        <h3 class="panel-title">CEH <span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/KuVBVVdCgr5KQP1YMDsf7c" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/Microsoft_11zon.jpg" /></div>
        <h3 class="panel-title">Microsoft <span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/GH2aNOTj7NbEr4e2aJzXHj" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/Huawei_11zon.jpg" /></div>
        <h3 class="panel-title">Huawei <span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/KcpvEbxFdJW0DZCyC4QWic" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
     <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/Oracle_11zon.jpg" /></div>
        <h3 class="panel-title">Oracle <span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/KcpvEbxFdJW0DZCyC4QWic" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/vmware-workstation-png-logo-7_11zon.jpg" /></div>
        <h3 class="panel-title">VMware <span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/IdP70waHkna1M2a8MyDh67" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/HP_11zon.jpg" /></div>
        <h3 class="panel-title">HP <span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/DgJoHYR8j4q3j1Qm10n2ts" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/people-cert-logo-png_seeklogo-536035_11zon_11zon.png" /></div>
        <h3 class="panel-title">PeopleCert<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/HrW40rNzkrf7xKArsSRPfW" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/rims-crmp-digital-badge.tmb-small_11zon.png" /></div>
        <h3 class="panel-title">RIMS-CRMP<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/FdXEgeOJbhL43ZdMdccxnA" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (22)_11zon.png" /></div>
        <h3 class="panel-title">Adobe<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/KRomgpgc0Q4551WVWBcV5c" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (1)_11zon.jpg" /></div>
        <h3 class="panel-title">CDMP<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/J54hC74UfzFIoioAhTi8S8" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (15)_11zon.png" /></div>
        <h3 class="panel-title">Hashicorp<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/KB7HUDf6Rm4GZ0aqdnzu4X" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (16)_11zon.png" /></div>
        <h3 class="panel-title">LINUX-LPI<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/BWh02Pq9aql4R6r7zDwDxc" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (17)_11zon.png" /></div>
        <h3 class="panel-title">LEED<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/G7uJqACqbUiLDH2lb3BrGk" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (18)_11zon.png" /></div>
        <h3 class="panel-title">GED<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/ICRmakLbrw44q2aAgQiPRP" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (19)_11zon.png" /></div>
        <h3 class="panel-title">NAHQ<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/IM2eMr48E9V0C6EpTBxog3" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (20)_11zon.png" /></div>
        <h3 class="panel-title">IIBA<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/DYRPDEfeIeFJ442HmxSFR1" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/Round-logo-HiRes-1692x1692-1_11zon.png" /></div>
        <h3 class="panel-title">IAM<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/J6FcsUdNwho86AcRhsOOdV" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (2)_11zon.jpg" /></div>
        <h3 class="panel-title">GIAC<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/IoZs0X5CtaNCUcsCkBgXmn" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="#" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (23)_11zon.png" /></div>
        <h3 class="panel-title">Dell<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/K8Fsb2kiojNLZ77wkLNksL" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (24)_11zon.png" /></div>
        <h3 class="panel-title">AVIXA<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/JlS18NGUjPx7Xde2cwrPKJ" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/flmliuxz_400x400_11zon.jpg" /></div>
        <h3 class="panel-title">AIPP<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/EbwFGNdlJHK1FUFpqYwBoy" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (25)_11zon.png" /></div>
        <h3 class="panel-title">AIPMM<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/IYklqkFVfMIAbQiCydkqal" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/acfe-badge_11zon.png" /></div>
        <h3 class="panel-title">ACFE<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/FnMx3NlxzY922abtRAhbwH" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (4)_11zon.jpg" /></div>
        <h3 class="panel-title">ACAMS<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/Eefx8mWPUGFFVJqDAlseiY" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/image (1).png" /></div>
        <h3 class="panel-title">AAI - The Institutes<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/Ja18JDjxGDj2RraHe4w5Bo" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/CompTIA.png" /></div>
        <h3 class="panel-title">Comptia<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/GGP1Qjl6d6q09jwUrtIWFc?mode=wwt" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/EC COUNCIL.png" /></div>
        <h3 class="panel-title">Ec council<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/BgixM5RthxrIX2wtW4IQhZ?mode=wwt" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/images (26).png" /></div>
        <h3 class="panel-title">CCSP<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/EY9035vkkaZBW9SsZ9ssz3?mode=wwt" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="col-sm-6 col-md-4">
      <div class="panel study-card main_flex1">
        <div class="panel-heading top_flex"> <div class=""><img src="images/Nvidia_logo.svg" /></div>
        <h3 class="panel-title">NVIDIA<span class="sub">Study Group</span></h3></div>
        <ul class="list-group study-links">
          <li class="list-group-item">
            <a class="study-link" href="https://t.me/chinesedumps/11" target="_blank" rel="noopener" title="Telegram">
              <img src="<?php echo BASE_URL; ?>images/telegram.png" alt="Telegram"> 
            </a>
          </li>
         
          <li class="list-group-item">
            <a class="study-link" href="https://chat.whatsapp.com/L26LDSjG7EX1YLAvGMI71J" target="_blank" rel="noopener" title="WhatsApp">
              <img src="<?php echo BASE_URL; ?>images/whatsapp.png" alt="WhatsApp">
            </a>
          </li>
           <li class="list-group-item">
            <a class="study-link" target="_blank" rel="noopener" href="https://teams.live.com/l/invite/FEAC_gibgUbhvkbjxA?v=g1" title="Microsoft Teams">
              <img src="<?php echo BASE_URL; ?>images/business.png" alt="Microsoft Teams">
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="mb-20"></div>
</div>

<? include("includes/footer.php");?>

