import React from 'react';
import Logo from '../../assets/svg/Logo.svg'
import './Footer.css'
import Qr from '../../assets/images/qr.jpeg'
import {useTranslation} from "react-i18next";
import {useNavigate} from "react-router-dom";
const Footer = () => {
    const { t } = useTranslation()
    const navigate = useNavigate()
    return (
    <footer className='pb-4' style={{background: '#E7F5E9'}}>
        <div className='mx-auto  ' style={{width:'89%'}}>
            <div
                className='d-flex justify-content-between flex-column flex-md-row custom-border align-items-start align-items-md-center gap-2 col-12 pt-4 pb-4'>
                <img src={Logo} alt={'logo'} className='footer-logo' onClick={() => navigate('/')}/>

                <div className='d-flex align-items-center gap-3 justify-content-center'>
                    <div className='white-circle'>
                        <i className="fa-brands  footer-custom-icon fa-facebook-f"></i>
                    </div>
                    <div className='white-circle'>
                        <i className="fa-brands  footer-custom-icon fa-youtube"></i>
                    </div>
                    <div className='white-circle'>
                        <i className="fa-brands footer-custom-icon fa-linkedin-in"></i>
                    </div>
                </div>
            </div>
            <div className='col-12 d-flex justify-content-between align-items-center'>
                <div className='d-flex mt-2 align-items-center gap-2'>
                    <a href="mailto:info@gfa.org.ge" target="_blank" rel="noopener noreferrer">
                        <i className="fa-solid footer-custom-icon fa-envelope"></i>
                    </a>
                    <p className='p-0 m-0 footer-text'>info@gfa.org.ge</p>

                </div>

                <p className='p-0 m-0 developed-text'>ყველა უფლება დაცულია "საქართველოს ფერმერთა ასოციაცია"</p>
                <div className='d-flex align-items-center gap-2 mt-2'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#348e38"
                         className="bi bi-telephone-fill" viewBox="0 0 16 16">
                        <path fillRule="evenodd"
                              d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                    </svg>
                    <p className='p-0 m-0 footer-text'>+995 593 122 122</p>
                </div>
            </div>


            {/*<div className='mt-4 d-flex align-items-center justify-content-between  custom-border col-12'>*/}

            {/*    <div*/}
            {/*        className='mt-4 d-flex flex-md-row flex-column  align-items-start align-items-xs-center justify-content-between col-12 pb-4'>*/}
            {/*        <div className='mt-4' style={{height:'132px'}}>*/}
            {/*            <h4 className='footer-title'>{t('modules')}</h4>*/}

            {/*            <div className='d-flex align-items-center gap-2 mt-3'>*/}
            {/*                <p className='p-0 m-0 footer-text'>{t('plantBreeding')}</p>*/}
            {/*            </div>*/}

            {/*            <div className='d-flex align-items-center gap-2 mt-2'>*/}
            {/*                <p className='p-0 m-0 footer-text'>{t('honeyStandard')}</p>*/}
            {/*            </div>*/}

            {/*            <div className='d-flex align-items-center gap-2 mt-2'>*/}
            {/*                <p className='p-0 m-0 footer-text'>{t('milkStandard')}</p>*/}
            {/*            </div>*/}

            {/*        </div>*/}


            {/*        <div className='mt-4' style={{height: '132px'}}>*/}
            {/*            <h4 className='footer-title'>{t('menu')}</h4>*/}
            {/*            <div className='d-flex align-items-center gap-2 mt-3'>*/}
            {/*                <p className='p-0 m-0 footer-text'>მთავარი</p>*/}
            {/*            </div>*/}

            {/*            <div className='d-flex align-items-center gap-2 mt-2'>*/}
            {/*                <p className='p-0 m-0 footer-text'>ჩვენს შესახებ</p>*/}
            {/*            </div>*/}

            {/*            <div className='d-flex align-items-center gap-2 mt-2'>*/}
            {/*                <p className='p-0 m-0 footer-text'>მოდულები</p>*/}
            {/*            </div>*/}

            {/*        </div>*/}


            {/*        <div className='mt-4' style={{height: '132px'}}>*/}
            {/*            <h4 className='footer-title'>{t('contactUs')}</h4>*/}
            {/*            <div className='d-flex align-items-center gap-2 mt-3'>*/}
            {/*                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#348e38"*/}
            {/*                     className="bi bi-geo-alt-fill" viewBox="0 0 16 16">*/}
            {/*                    <path*/}
            {/*                        d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10m0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>*/}
            {/*                </svg>*/}
            {/*                <p className='p-0 m-0 footer-text'>2072 Pinnickinick Street</p>*/}
            {/*            </div>*/}

            {/*<div className='d-flex align-items-center gap-2 mt-2'>*/}
            {/*    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#348e38"*/}
            {/*         className="bi bi-telephone-fill" viewBox="0 0 16 16">*/}
            {/*        <path fillRule="evenodd"*/}
            {/*              d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>*/}
            {/*    </svg>*/}
            {/*    <p className='p-0 m-0 footer-text'>xxx xxx-xxx-xxx</p>*/}
            {/*</div>*/}

            {/*            <div className='d-flex align-items-center gap-2 mt-2'>*/}
            {/*                <i className="fa-solid footer-custom-icon fa-envelope"></i>*/}
            {/*                <p className='p-0 m-0 footer-text'>youremail@gmail.com</p>*/}
            {/*            </div>*/}

            {/*        </div>*/}

            {/*        <div className='qr-container mt-4 d-flex flex-column justify-content-center align-items-center'*/}
            {/*             style={{ height: '132px'}}>*/}
            {/*            <h4 className='footer-title'>სერტიფიცირებული ფერმერები</h4>*/}
            {/*            <img src={Qr} alt='qr' className='qr-size'/>*/}
            {/*        </div>*/}
            {/*    </div>*/}


            {/*</div>*/}

        </div>
    </footer>
    );
};

export default Footer;