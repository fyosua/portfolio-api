<?php

namespace App\DataFixtures;

use App\Entity\About;
use App\Entity\Experience;
use App\Entity\Skill;
use App\Entity\SkillCategory;
use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Experiences Data
        $experiencesData = [
            [
                'role' => 'Technical Specialist - Google Technical Solutions (Rehired)',
                'company' => 'TDCX',
                'location' => 'Kuala Lumpur',
                'date' => 'Sep 2024 - Present',
                'summary' => 'Rehired to resume responsibilities as a Technical Specialist, highlighting my proven expertise and performance in Google Tracking solutions.',
                'responsibilities' => [
                    [
                        'point' => 'Continue specializing in implementing and managing Google Tracking tools, including Google Ads, Google Analytics, Google Tag Manager, and Google Merchant Center.'
                    ],
                    [
                        'point' => 'Maintain exceptional performance in consulting, technical support, and communication, reinforcing client trust and satisfaction.'
                    ]
                ],
            ],
            [
                'role' => 'Technical Hosting Support Engineer',
                'company' => 'PT. World Host Group',
                'location' => 'Bali',
                'date' => 'Feb 2024 - Aug 2024',
                'summary' => 'I excel at promptly resolving diverse client inquiries across hosting services, ensuring uninterrupted operations for our valued clients.',
                'responsibilities' => [
                    [
                        'point' => 'Offer proficient technical support to clients, with a focus on diagnosing and resolving issues pertaining to various aspects including:',
                        'subPoints' => [
                            'Domain management',
                            'Shared and VPS hosting environments',
                            'Control panel platforms such as cPanel, WHM, Plesk, and SolidCP',
                            'Rectifying website errors and malfunctions',
                            'Configuration and troubleshooting of mail servers',
                            'Assignment and management of DNS clusters.'
                        ]
                    ],
                    [
                        'point' => 'Utilize advanced ticketing systems to manage and prioritize client inquiries efficiently, ensuring prompt resolution.'
                    ],
                    [
                        'point' => 'Engage with the global English-speaking market through diverse communication channels, including ticketing, calls, and live chat support.'
                    ]
                ],
            ],
            [
                'role' => 'Technical Specialist - Google Technical Solutions',
                'company' => 'TDCX',
                'location' => 'Kuala Lumpur',
                'date' => 'Sep 2022 - Nov 2023',
                'summary' => 'Dedicated myself to specializing in Google Tracking products.',
                'responsibilities' => [
                    [
                        'point' => 'Proficiently implemented Google Tracking Tools, including Google Ads, Google Analytics, Google Tag Manager, and Google Merchant Center.'
                    ],
                    [
                        'point' => 'Excelled in providing consulting services with strong business communication and product pitching abilities.'
                    ]
                ],
            ],
            [
                'role' => 'Back End Developer',
                'company' => 'PT. Uniktif Media (Unictive)',
                'location' => 'Jakarta',
                'date' => 'Feb 2021 - Feb 2022',
                'summary' => 'Designed and enhanced server-side applications, with proficiency in PHP, JavaScript, and frameworks like Laravel and React.',
                'responsibilities' => [
                    [
                        'point' => 'Prioritized seamless API integration between the website\'s back-end and front-end to optimize user experience.'
                    ],
                    [
                        'point' => 'Skilled in deploying websites via Nginx, configuring SSL, and occasionally using PM2 for deployment management.'
                    ],
                    [
                        'point' => 'Successfully implemented mail server setups for CMS systems, facilitating user management, invitations, and order notifications.'
                    ],
                    [
                        'point' => 'Expertise in data infrastructure enables swift and accurate analysis of technical documents.'
                    ]
                ],
            ],
            [
                'role' => 'IT Support',
                'company' => 'PT Dewaweb',
                'location' => 'Jakarta',
                'date' => 'Feb 2018 - Feb 2019',
                'summary' => 'Committed to providing high-quality IT support, delivering technical assistance via phone and email within a CRM system.',
                'responsibilities' => [
                    [
                        'point' => 'Proficient in troubleshooting server products (cPanel, WHMPanel, CloudFlare, SSL, Redis), primarily for WordPress websites.'
                    ],
                    [
                        'point' => 'Skilled in utilizing CRM systems like WHMCS in hosting support environments.'
                    ],
                    [
                        'point' => 'Possess knowledge in domain management, DNS record systems, and various payment platforms.'
                    ]
                ],
            ],
        ];

        foreach ($experiencesData as $expData) {
            $experience = new Experience();
            $experience->setRole($expData['role']);
            $experience->setCompany($expData['company']);
            $experience->setLocation($expData['location']);
            $experience->setDate($expData['date']);
            $experience->setSummary($expData['summary']);
            $experience->setResponsibilities($expData['responsibilities']);
            $manager->persist($experience);
        }

        // Skills Data
        $skillCategoriesData = [
            [
                'title' => 'Google Ecosystem',
                'icon' => 'SiGoogle',
                'skills' => [
                    ['name' => 'Google Ads', 'icon' => 'SiGoogleads'],
                    ['name' => 'Google Analytics', 'icon' => 'SiGoogleanalytics'],
                    ['name' => 'Google Tag Manager', 'icon' => 'HiAdjustments'],
                    ['name' => 'Google Merchant Center', 'icon' => 'HiDesktopComputer'],
                ],
            ],
            [
                'title' => 'Web & Frontend',
                'icon' => 'HiCode',
                'skills' => [
                    ['name' => 'Next.JS', 'icon' => 'SiNextdotjs'],
                    ['name' => 'Laravel', 'icon' => 'SiLaravel'],
                    ['name' => 'HTML & CSS', 'icon' => 'HiCode'],
                    ['name' => 'Wordpress', 'icon' => 'SiWordpress'],
                    ['name' => 'Rest API', 'icon' => 'HiCode'],
                ],
            ],
            [
                'title' => 'Server & Hosting',
                'icon' => 'HiServer',
                'skills' => [
                    ['name' => 'WHM & cPanel', 'icon' => 'SiCpanel'],
                    ['name' => 'Linux System Admin', 'icon' => 'SiLinux'],
                    ['name' => 'Network Infrastructure', 'icon' => 'HiServer'],
                    ['name' => 'System Monitoring', 'icon' => 'HiDesktopComputer'],
                    ['name' => 'Bash Scripting', 'icon' => 'HiCode'],
                ],
            ],
            [
                'title' => 'Tools & General Tech',
                'icon' => 'HiDatabase',
                'skills' => [
                    ['name' => 'Git', 'icon' => 'SiGit'],
                    ['name' => 'MySQL', 'icon' => 'SiMysql'],
                    ['name' => 'Technical Support', 'icon' => 'HiDesktopComputer'],
                    ['name' => 'Payment Platforms', 'icon' => 'HiDesktopComputer'],
                ],
            ],
        ];

        foreach ($skillCategoriesData as $catData) {
            $category = new SkillCategory();
            $category->setTitle($catData['title']);
            $category->setIcon($catData['icon']);
            $manager->persist($category);

            foreach ($catData['skills'] as $skillData) {
                $skill = new Skill();
                $skill->setName($skillData['name']);
                $skill->setIcon($skillData['icon']);
                $skill->setCategory($category);
                $manager->persist($skill);
            }
        }

        // About Me Data
        $about = new About();
        $about->setContent(
            'Experienced Website Developer, Technical Specialist, and Technical Hosting Specialist with a strong background in web technologies. Proven track record in website development, management, and technical hosting support. Expertise includes overseeing technical implementation, efficiently troubleshooting complex issues, and providing exceptional customer service. Enthusiastic about leveraging knowledge and experience to contribute to innovative digital projects and provide comprehensive technical hosting support.'
        );
        $manager->persist($about);

        // Profile Data
        $profile = new Profile();
        $profile->setName('Yosua Ferdian');
        $profile->setTitle('Technical Specialist & Web FullStack Developer');
        $profile->setEmail('ferdianyosua@gmail.com');
        $profile->setPhone('+601127817121');
        $profile->setLinkedin('https://www.linkedin.com/in/yosua-ferdian-a1a929116/');
        $manager->persist($profile);
	
        $manager->flush();
    }
}
