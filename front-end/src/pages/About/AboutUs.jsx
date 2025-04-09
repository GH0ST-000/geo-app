import React, {useEffect, useState} from 'react';
import './About.css'
import {useTranslation} from "react-i18next";
import AOS from "aos";
import Lottie from "lottie-react";
import Loader from "../../component/laoder/Loader.jsx";
import AboutGeoGap from "../../component/AboutGeoGap/AboutGeoGap.jsx";
import Timeline from "../../component/Timeline/Timeline.jsx";
// #E7F5E9
const AboutUs = () => {
    const [loader,setLoader] = useState(true);
    const { t } = useTranslation();


    useEffect(() => {
        setTimeout(() => {
            setLoader(false)
        },3000)

        AOS.init({
            duration: 1000,
            offset: 100,
            easing: "ease-in-out",
            once: false,
        });
    }, []);
    return (
        <>

                    <>
                        <AboutGeoGap />
                        <div className='col-11 mx-auto' style={{marginTop:'80px', marginBottom:'120px'}}>
                            <h1  className='about-title text-center p-0 m-0'>{t('workProcess')}</h1>
                            <p
                               className='about-text text-center pt-2 m-0'>{t('stepsToGetCertificate')}</p>

                            {/*მშობელი*/}
                            <div className='mt-4 d-flex gap-4 step-wrapper'>
                                {/*შიდა მარცხენა დივი*/}
                                <div className="d-flex col-12 col-lg-6 align-items-center gap-5">

                                    <div
                                        className='custom-step-about-container d-flex justify-content-center align-items-center'>
                                        <i className="fa-solid fa-award"
                                           style={{fontSize: '60px', color: '#529F56'}}></i>

                                        <div
                                            className='step-quantity d-flex justify-content-center align-items-center'>01
                                        </div>
                                    </div>
                                    <div>
                                        <h2 className='step-title'>Set Design Target</h2>
                                        <p className='step-text'>Lorem Ipsum is simply dummy text of free available
                                            market
                                            typesetting
                                            industry has been the</p>
                                    </div>
                                </div>

                                <div className="d-flex col-12 col-lg-6 align-items-center gap-5">

                                    <div
                                        className='custom-step-about-container d-flex justify-content-center align-items-center'>
                                        <i className="fa-solid fa-award"
                                           style={{fontSize: '60px', color: '#529F56'}}></i>

                                        <div
                                            className='step-quantity d-flex justify-content-center align-items-center'>02
                                        </div>
                                    </div>
                                    <div>
                                        <h2 className='step-title'>Set Design Target</h2>
                                        <p className='step-text'>Lorem Ipsum is simply dummy text of free available
                                            market
                                            typesetting
                                            industry has been the</p>
                                    </div>
                                </div>
                            </div>


                            <div className='mt-4 d-flex gap-4 step-wrapper'>
                                {/*შიდა მარცხენა დივი*/}
                                <div className="d-flex col-12 col-lg-6 align-items-center gap-5">

                                    <div
                                        className='custom-step-about-container d-flex justify-content-center align-items-center'>
                                        <i className="fa-solid fa-award"
                                           style={{fontSize: '60px', color: '#529F56'}}></i>

                                        <div
                                            className='step-quantity d-flex justify-content-center align-items-center'>03
                                        </div>
                                    </div>
                                    <div>
                                        <h2 className='step-title'>Set Design Target</h2>
                                        <p className='step-text'>Lorem Ipsum is simply dummy text of free available
                                            market
                                            typesetting
                                            industry has been the</p>
                                    </div>
                                </div>

                                <div className="d-flex col-12 col-lg-6 align-items-center gap-5">

                                    <div
                                        className='custom-step-about-container d-flex justify-content-center align-items-center'>
                                        <i className="fa-solid fa-award"
                                           style={{fontSize: '60px', color: '#529F56'}}></i>

                                        <div
                                            className='step-quantity d-flex justify-content-center align-items-center'>04
                                        </div>
                                    </div>
                                    <div>
                                        <h2 className='step-title'>Set Design Target</h2>
                                        <p className='step-text'>Lorem Ipsum is simply dummy text of free available
                                            market
                                            typesetting
                                            industry has been the</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <Timeline />
                    </>



        </>
    );
};

export default AboutUs;