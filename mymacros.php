<?php
// mymacros.sty	 1.1   2021/04/28
// Andrew Lounsbury

// This file was not generated from source code. 
// This software is contributed to the public domain.  

// Marking this file as a package
// \NeedsTeXFormat{LaTeX2e}
// \ProvidesPackage{mymacros}[2021/04/28 1.1 miscellaneous commands]

// This allows commands that use things from these packages to work even if we 
// haven't put them in \usepackage{} in the preamble. 
\RequirePackage{forloop, mathrsfs, mathtools, nicefrac, xcolor}
// Otherwise, we could list packages we often use to avoid having to include them 
// in the preamble of our tex files. 
// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *

// In other words, the use of \usepackage{} for packages other than mymacros at the  * // beginning of LaTeX_for_Undergraduates.tex is merely there for the sake of example.*
// * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
// \RequirePackage{amsmath, amsfonts, amssymb, amsthm, cancel, calligra, enumitem, fancyvrb, fontenc, graphicx, pagecolor, pifont, totcount, upgreek, ulem,  verbatim, verse}
// \PassOptionsToPackage{T1}{fontenc}
// \PassOptionsToPackage{normalem}{ulem}

// GENERAL COMMANDS //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// From enumitem package
// Fixing indentation in enumerate and itemize environments
// \setlist{  
// 	listparindent=\parindent,
// 	parsep=0pt,
// }

// For <...> options in LaTeX_for_Undergraduates.tex
\newcommand*{\opt}[1]{$\langle$\textit{#
1}$\rangle$}
\newcommand*{\mopt}[1]{\text{$\langle$\textit{#
1}$\rangle$}}

// \settoheight{\lineheight}{X}

// Troubleshooting
\newcommand{\ee}{\end{enumerate}}
\newcommand{\ed}{\end{document}}

// LaTeX Workshop autocompletes \par to \paragraph{}, which gets annoying. 
\newcommand{\p}{\par}

// Horizontal separator with option to add label
\newcommand*{\hl}[1][\unskip]{//
	\noindent\textbf{#
1} \\//
	\makebox{//
		\rule[1.5ex]{0.75\paperwidth}{0.8pt}//
	}//
}

// \renewcommand overrides a command that has already been defined. 
// In the proof environment, \qedsymbol defaults to a white square. 
// I prefer a black square, so I change it here. 
\renewcommand{\qedsymbol}{$\blacksquare$}
// but these allow me to easily change it to other things
\newcommand{\whiteqed}{\renewcommand{\qedsymbol}{$\square$}}
\newcommand{\noqed}{\renewcommand{\qedsymbol}{}}

// Environments -----------------------------------------------------------------
// Creating \begin{...} \end{...} environments with a few defaults provided by 
// \newtheorem
// * removes numbering
// The first {} contains the name of the environment. 
// The second {} contains what will be displayed. 
// \newtheorem{claim}{Claim} 	// numbered
// \newtheorem*{claim*}{Claim} // not numbered
// \newtheorem*{conjecture*}{Conjecture}
// \newtheorem{corollary}{Corollary}
// \newtheorem*{corollary*}{Corollary}
// \newtheorem{lemma}{Lemma}
// \newtheorem*{lemma*}{Lemma}
// \newtheorem{note}{Note}
// \newtheorem{problem}{Problem}
// \newtheorem{proposition}{Proposition}
// \newtheorem*{proposition*}{Proposition}
// \newtheorem{theorem}{Theorem}
// \newtheorem*{theorem*}{Theorem}

// \theoremstyle{definition} 	// removes italics from the following
// \newtheorem{case}{Case}
// \newtheorem{counterexample}{Counterexample}
// \newtheorem*{counterexample*}{Counterexample}
// \newtheorem{definition}{Definition}
// \newtheorem*{definition*}{Definition}
// \newtheorem{example}{Example}
// \newtheorem*{example*}{Example}
// \newtheorem{examples}{Examples}
// \newtheorem*{examples*}{Examples}
// \newtheorem{nonexample}{Non-Example}
// \newtheorem*{nonexample*}{Non-Example}
// \newtheorem*{fact*}{Fact}
// \newtheorem{notation}{Notation}
// \newtheorem*{notation*}{Notation}
// \theoremstyle{remark}		// italicized title, upright text
// \newtheorem{remark}{Remark}
// \newtheorem*{remark*}{Remark}

// Don't use these \newtheorem environments. 
// Use the \newenvironment's below. 
// \newtheorem*{pfcases}{Proof by Cases}
// \newtheorem*{pfcontradiction}{Proof by Contradiction}
// \newtheorem*{pfcombinatorial}{Proof (Combinatorial)}
// \newtheorem*{pfcontrapositive}{Proof by Contrapositive}
// \newtheorem*{pfdirect}{Proof (Direct)}
// \newtheorem*{pfinduction}{Proof by Induction}
// // Proof environments with the technique specified
// \newenvironment*{pfca}
// 	{\pushQED{\qed}\pfcases}
// 	{\popQED\endpfcases}
// \newenvironment*{pfcd}
// 	{\pushQED{\qed}\pfcontradiction}
// 	{\popQED\endpfcontradiction}
// \newenvironment*{pfco}
// 	{\pushQED{\qed}\pfcombinatorial}
// 	{\popQED\endpfcombinatorial}
// \newenvironment*{pfcp}
// 	{\pushQED{\qed}\pfcontrapositive}
// 	{\popQED\endpfcontrapositive}
// \newenvironment*{pfd}
// 	{\pushQED{\qed}\proof}
// 	{\popQED\endpfdirect}
// \newenvironment*{pfi}
// 	{\pushQED{\qed}\pfinduction}
// 	{\popQED\endpfinduction}

// // Color-coded environments
// \definecolor{darkpastelgreen}{rgb}{0.01, 0.75, 0.24}
// \definecolor{ballblue}{rgb}{0.13, 0.67, 0.8}

// \theoremstyle{plain} // adds italics to the following
// \newtheorem*{cconjecture*}{\colorbox{orange}{Conjecture}}
// \newtheorem{ccorollary}{\colorbox{orange}{Corollary}}
// \newtheorem*{ccorollary*}{\colorbox{orange}{Corollary}}
// \newtheorem{clemma}{\colorbox{orange}{Lemma}}
// \newtheorem*{clemma*}{\colorbox{orange}{Lemma}}
// \newtheorem{cnote}{\colorbox{blue}{Note}}
// \newtheorem{cproposition}{\colorbox{pink}{Proposition}}
// \newtheorem*{cproposition*}{\colorbox{pink}{Proposition}}
// \newtheorem{ctheorem}{\colorbox{pink}{Theorem}}
// \newtheorem*{ctheorem*}{\colorbox{pink}{Theorem}}

// \theoremstyle{definition} // removes italics from the following
// \newtheorem{ccase}{Case}
// \newtheorem*{cfact*}{\colorbox{cyan}{Fact}}
// \newtheorem{cexample}{\colorbox{darkpastelgreen}{Example}}
// \newtheorem*{cexample*}{\colorbox{darkpastelgreen}{Example}}
// \newtheorem{cnonexample}{\colorbox{darkpastelgreen}{Non-Example}}
// \newtheorem*{cnonexample*}{\colorbox{red}{Non-Example}}
// \newtheorem*{cremark*}{\colorbox{cyan}{Remark}}
//-----------------------------------------------------------------

// For inductive proofs and recursive definitions
\newcommand{\bc}{\textbf{\underline{Base case}: }}
\newcommand{\ind}{\textbf{\underline{Induction step}: }}
\newcommand{\basis}{\textbf{\underline{Basis}: }}
\newcommand{\rec}{\textbf{\underline{Recursive step}: }}

// Ways to avoid \text{} in display-math
\newcommand{\as}{\text{ as}\ }
\newcommand{\bec}{\text{ because}\ }
\newcommand{\but}{\text{ but}\ }
\newcommand{\const}{\text{ const}\ }
\newcommand{\countable}{\text{ countable}\ }
\newcommand{\each}{\text{ each}\ }
\newcommand{\et}{\text{ and}\ }
\newcommand{\for}{\text{ for}\ }
\newcommand{\some}{\text{ some}\ }
\newcommand{\fs}{\text{ for some}\ }
\newcommand{\on}{\text{ on}\ }
\newcommand{\ona}{\text{ on a}\ }
\newcommand{\ow}{\text{o/w}\ }			// otherwise
\newcommand{\st}{\text{s.t.}\ }			// such that
\newcommand{\mst}{\text{ s.t.}\ }		// math such that
\newcommand{\ou}{\text{ or}\ }
\renewcommand{\over}{\text{ over}\ }
\newcommand{\so}{\text{ so}\ }
\newcommand{\tms}{\text{ times}\ } 		// for \underbrace{...}_{n times}
\newcommand{\undef}{\text{undef.}\ }
\newcommand{\with}{\text{ with}\ }
\newcommand{\where}{\text{ where}\ }
\newcommand{\wrt}{\text{ with respect to}\ }

// Things to stack on =, <, \le, etc. 
\newcommand{\lh}{\stackrel{\text{\tiny L'H}}}	// L'Hôpital's Rule
\newcommand{\pf}{\stackrel{\text{\tiny PF}}}	// Pascal's Formula
\newcommand{\ti}{\stackrel{\text{\tiny TI}}}	// Triangle Inequality
\newcommand{\one}{\stackrel{\text{\tiny \ensuremath{(1)}}}}
\newcommand{\two}{\stackrel{\text{\tiny \ensuremath{(2)}}}}
\newcommand{\three}{\stackrel{\text{\tiny \ensuremath{(3)}}}}

// Black-board bold
\newcommand{\C}{\mathbb{C}} 	// complex numbers
\newcommand{\F}{\mathbf{F}}		// bold F
\newcommand{\N}{\mathbb{N}} 	// natural numbers
\newcommand{\bP}{\mathbb{P}}
\newcommand{\Q}{\mathbb{Q}} 	// etc.
\newcommand{\R}{\mathbb{R}}
\newcommand{\W}{\mathbb{W}}
\newcommand{\Z}{\mathbb{Z}}

// Miscellaneous
\newcommand{\bs}{\boldsymbol}
\newcommand{\f}{\frac}			// \f{numerator}{denominator}
\newcommand{\inv}{^{-1}}
\newcommand*{\lcm}[1]{\mathrm{lcm}\left\{#
1\right\}}	// least common multiple
\newcommand{\mbf}{\mathbf} 								// math bold face
\newcommand*{\ol}[1]{\overline{#
1}}
\newcommand*{\ttt}[1]{\texttt{#
1}}
\newcommand*{\ul}[1]{\underline{#
1}}
\newcommand{\vn}{\varnothing}
\newcommand{\wt}{\widetilde}

// My notations
\newcommand*{\msum}[3]{\sum\limits_{#
1=#
2}^{#
3}} 		// my sum
\newcommand*{\mprod}[3]{\prod\limits_{#
1=#
2}^{#
3}}		// my prod
\newcommand*{\mcap}[3]{\cap_{#
1=#
2}^{#
3}}				// etc.
\newcommand*{\mcup}[3]{\cup_{#
1=#
2}^{#
3}}
\newcommand*{\mbcap}[3]{\bigcap\limits_{#
1=#
2}^{#
3}}
\newcommand*{\mbcup}[3]{\bigcup\limits_{#
1=#
2}^{#
3}}
\newcommand*{\mset}[4]{\left\{#
1\right\}_{#
2=#
3}^{#
4}}
// Default infinity for the upper bounds
\newcommand*{\msumi}[2]{\msum{#
1}{#
2}{\infty}}
\newcommand*{\mprodi}[2]{\mprod{#
1}{#
2}{\infty}}
\newcommand*{\mcapi}[2]{\mcap{#
1}{#
2}{\infty}}
\newcommand*{\mcupi}[2]{\mcup{#
1}{#
2}{\infty}}
\newcommand*{\mbcapi}[2]{\mbcap{#
1}{#
2}{\infty}}
\newcommand*{\mbcupi}[2]{\mbcup{#
1}{#
2}{\infty}}
\newcommand*{\mseti}[3]{\mset{#
1}{#
2}{#
3}{\infty}}

\newcommand{\bcup}{\bigcup\limits}
\newcommand{\bcap}{\bigcap\limits}
\newcommand{\Int}{\int\limits}
\newcommand{\Prod}{\prod\limits}
\newcommand{\Sum}{\sum\limits}

// Package-specific
\newcommand{\nf}{\nicefrac} 				// nicefrac
\newcommand{\cmark}{\ding{51}\ }				// pifont
\newcommand{\xmark}{\ding{55}\ }
\newcommand*{\cb}[2]{\colorbox{#
1}{#
2}} 	// xcolor
\newcommand*{\cbb}[1]{\colorbox{blue}{#
1}}
\newcommand*{\cbr}[1]{\colorbox{red}{#
1}}
\newcommand{\cbg}{\cb{darkpastelgreen}}
\newcommand*{\cby}[1]{\colorbox{yellow}{#
1}}
\newcommand*{\tc}[2]{\textcolor{#
1}{#
2}}
\newcommand*{\tcb}[1]{\textcolor{blue}{#
1}}
\newcommand{\tcg}{\tc{darkpastelgreen}}
\newcommand*{\tcr}[1]{\textcolor{red}{#
1}}
// math mode
\newcommand*{\mcb}[2]{\colorbox{#
1}{$#
2$}}
\newcommand*{\mcbb}[1]{\colorbox{blue}{$#
1$}}
\newcommand*{\mcbr}[1]{\colorbox{red}{$#
1$}}
\newcommand*{\mcby}[1]{\colorbox{yellow}{$#
1$}}
\newcommand*{\mtc}[2]{\textcolor{#
1}{$#
2$}}
\newcommand*{\mtcb}[1]{\textcolor{blue}{$#
1$}}
\newcommand*{\mtcr}[1]{\textcolor{red}{$#
1$}}
\DeclareOption{b}{ 
	\pagecolor{black}
	\color{white}
}
\DeclareOption*{\PackageWarning{mymacros}{Unknown '\CurrentOption'}}
\ProcessOptions\relax

//--------------------------------------------------------------------------
// Polish notation for responsive block delimiters
\newcommand*{\abs}[1]{\left\lvert#
1\right\rvert}		// absolute value
\newcommand*{\ceil}[1]{\left\lceil#
1\right\rceil}		// ceiling
\newcommand*{\floor}[1]{\left\lfloor#
1\right\rfloor}	// floor
\newcommand*{\mang}[1]{\left<#
1\right>} 				// my angle bracket
\newcommand*{\mbrack}[1]{\left[#
1\right]}				// my bracket
\newcommand*{\mpar}[1]{\left(#
1\right)}					// my parentheses
\newcommand*{\norm}[1]{\left\lVert#
1\right\rVert}		// norm

\newcommand*{\bpar}[1]{\boldsymbol(#
1\boldsymbol)}

// \Mid is a very useful extensible \mid from the mathtools documentation 
// (originally \given)
// just to make sure it exists
\providecommand\Mid{}
// can be useful to refer to this outside \set
\newcommand\SetSymbol[1][]{//
	\nonscript\:#
1\vert//
	\allowbreak//
	\nonscript\://
	\mathopen{}//
}
\DeclarePairedDelimiterX{\set}[1]\{\}{//
	\renewcommand\Mid{\SetSymbol[\delimsize]}//
	#
1//
}
//-------------------------------------------------------------------------

// ALGEBRA
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
\renewcommand{\O}{\mathcal{O}}

// ALGEBRAIC GEOMETRY ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
\newcommand{\V}{\mathbf{V}}
\newcommand{\I}{\mathbf{I}}
\newcommand{\Va}{\V_a}
\newcommand{\Ia}{\I_a}
\newcommand*{\VaI}[1]{\V_a(\I(#
1))}
\newcommand*{\IaV}[1]{\I_a(\V(#
1))}
\newcommand*{\VI}[1]{\V(\I(#
1))}
\newcommand*{\IV}[1]{\I(\V(#
1))}

// Polynomial rings
\newcommand{\kx}{k[x]}
\newcommand{\ky}{k[y]}
\newcommand{\kz}{k[z]}
\newcommand{\kxy}{k[x,y]}
\newcommand{\kxz}{k[x,z]}
\newcommand{\kyz}{k[y,z]}
\newcommand{\kxyz}{k[x,y,z]}
\newcommand{\ring}{k[x_1,\dots,x_n]}
\newcommand{\ringz}{k[x_0,\dots,x_n]}
\newcommand{\Izero}{\left<x_0,\dots,x_n\right>}

// Polynomial rings over projective space
\newcommand*{\pring}[2]{\bP^{#
1}(#
2)}
\newcommand{\ptwok}{\bP^2(k)}
\newcommand{\pnk}{\bP^n(k)}
\newcommand{\por}{\bP^1(\R)}
\newcommand{\ptwor}{\bP^2(\R)}
\newcommand{\pthr}{\bP^3(\R)}
\newcommand{\pnr}{\bP^n(\R)}

\newcommand{\gln}{\mathrm{GL}(n,k)}
\DeclareMathOperator{\lm}{LM}		// leading monomial
\DeclareMathOperator{\lt}{LT}		// leading term
\DeclareMathOperator{\lc}{LC}		// leading coefficient
\DeclareMathOperator{\md}{multideg}	// degree for multivariate polynomial

// CALCULUS ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
\newcommand{\A}{\mathcal{A}}			// an algebra of functions
\newcommand{\va}{\scalebox{1.5}{$a$}} 	// variation of \A
\newcommand{\ab}{[a,b]}
\newcommand{\ar}{\mathscr{R}}
\newcommand{\ara}{\mathscr{R}(\alpha)}
\newcommand{\arr}{\mathcal{R}}
\newcommand{\B}{\mathcal{B}}
\DeclareMathOperator{\bd}{\mathrm{bd}}
\newcommand{\bdd}{bounded\ }
\DeclareMathOperator{\diam}{diam}
\newcommand{\E}{\mathcal{E}}
\newcommand{\ef}{\mathscr{F}}
\DeclareMathOperator{\ext}{\mathrm{ext}}
\newcommand{\fsc}{finite subcover\ }
\DeclareMathOperator{\intr}{\mathrm{int}}
\renewcommand{\L}{\mathscr{L}}
\newcommand{\M}{\mathfrak{M}}
\newcommand{\nbd}{neighborhood\ }
\newcommand{\U}{\mathscr{U}}
\newcommand{\oo}{[0,1]}
\newcommand{\seq}{(x_n)_{n=1}^\infty}
\newcommand{\seqf}{\bs(f_n(x)\bs)_{n=1}^\infty}
\newcommand{\spc}{(X,\tau)}

\newcommand{\duh}{Let $(X,d)$ be a metric space. }

// Linear algebra
\newcommand{\boldf}{\bs{\mathrm{f}}}
\newcommand{\Cdot}{\bs{\cdot}}
\newcommand*{\mvec}[1]{\bs{\mathrm{#
1}}}	// my vector
\newcommand{\e}{\bs{\mathrm{e}}}
\newcommand{\h}{\bs{\mathrm{h}}}
\newcommand{\x}{\bs{\mathrm{x}}}
\newcommand{\y}{\bs{\mathrm{y}}}
\newcommand{\z}{\bs{\mathrm{z}}}
\newcommand{\vf}{\vec{f}}
\newcommand{\vfp}{\vec{f^\prime}}
\newcommand{\vphi}{\vec{\phi}}

// Greek letters
\renewcommand{\a}{\alpha}
\renewcommand{\b}{\beta}
\newcommand{\D}{\Delta}
\newcommand{\Da}{\Delta\alpha}
\newcommand{\Dx}{\Delta x}
\newcommand{\ve}{\varepsilon}
\newcommand{\vp}{\varphi}
\newcommand{\vr}{\varrho}

// Limits
\newcommand*{\mlim}[1]{\lim\limits_{#
1}} 	// my limit
\newcommand{\Lim}{\lim\limits}
\newcommand{\Limsup}{\limsup\limits}
\newcommand{\Liminf}{\liminf\limits}
\newcommand{\limi}{\ul{\Lim}\ } 			// alternative liminf
\newcommand{\lims}{\ol\Lim\ } 				// alternative limsup
\newcommand{\Max}{\max\limits}
\newcommand{\Min}{\min\limits}

\newcommand{\Inf}{\inf\limits}
\newcommand{\Sup}{\sup\limits}
\newcommand*{\minf}[1]{\Inf_{#
1}}
\newcommand*{\msup}[1]{\Sup_{#
1}}

\newcommand*{\mto}[2]{#
1\to#
2} 				// my to
\newcommand*{\mtoi}[1]{\mto{#
1}{\infty}}
\newcommand{\nti}{\mtoi{n}}
\newcommand{\ttx}{\mto{t}{x}}
\newcommand{\xti}{\mtoi{x}}

\newcommand{\pto}{\xrightarrow{\text{\tiny PW}}} 	// pointwise convergence
\newcommand{\uto}{\xrightarrow{\text{unif}}} 		// uniform convergence

// Derivatives
\newcommand*{\dif}[1]{\,d#
1}
\newcommand{\da}{\,d\alpha}
\newcommand{\dx}{\,dx}
\newcommand{\dy}{\,dy}
\newcommand{\dz}{\,dz}
\newcommand{\dxy}{\,dxdy}
\newcommand{\dxz}{\,dxdz}
\newcommand{\dyz}{\,dydz}
\newcommand{\dxyz}{\,dxdydz}
\newcommand{\dt}{\,dt}
\newcommand{\du}{\,du}
\newcommand{\dv}{\,dv}

\newcommand*{\dd}[2]{\f{d#
1}{d#
2}}
\newcommand*{\mdd}[2]{\f{d^{#
1}}{d#
2^{#
1}}}
\newcommand{\ddx}{\f{d}{dx}}
\newcommand{\ddy}{\f{d}{dy}}
\newcommand{\ddz}{\f{d}{dz}}
\newcommand{\del}{\partial}
\newcommand*{\mdel}[2]{\f{\partial#
1}{\partial#
2}}
\newcommand*{\delx}[1]{\f{\partial#
1}{\parial x}}
\newcommand*{\dely}[1]{\f{\partial#
1}{\partial y}}
\newcommand*{\delz}[1]{\f{\partial#
1}{\partial z}}
\newcommand{\Del}{\nabla}

// Integrals
\newcommand*{\mint}[2]{\int\limits_{#
1}^{#
2}} 		// my integral
\newcommand{\mab}{\mint{a}{b}}
\newcommand*{\lint}[2]{\mint{\underline{#
1}}{#
2}} 	// lower Rieman integral
\newcommand{\lab}{\lint{a}{b}}
\newcommand*{\uint}[2]{\mint{#
1}{\ol{#
2}}} 			// upper
\newcommand{\uab}{\uint{a}{b}}

\newcommand*{\dint}[2]{\displaystyle\int_{#
1}^{#
2}} // display integral (no \limits)
\newcommand{\dab}{\dint{a}{b}}
\newcommand*{\dlint}[2]{\dint{\underline{#
1}}{#
2}}
\newcommand{\dlab}{\dlint{a}{b}}
\newcommand*{\duint}[2]{\dint{#
1}{\ol{#
2}}}
\newcommand{\duab}{\duint{a}{b}}

\newcommand*{\Lint}[2]{\int\limits_{\underline{#
1}}^{#
2}}
\newcommand{\Lab}{\Lint{a}{b}}
\newcommand*{\Uint}[2]{\int\limits_{#
1}^{\ol{#
2}}}
\newcommand{\Uab}{\Uint{a}{b}}

// Default upper bound of infinity
\newcommand*{\dinti}[1]{\dint{#
1}{\infty}} 		// display integral (no \limits)
\newcommand*{\minti}[1]{\mint{#
1}{\infty}}

\newcommand{\pint}{\mint{0}{1}} 				// probability integral
\newcommand{\piint}{\mint{0}{1}\mint{0}{1}}
\newcommand{\piiint}{\mint{0}{1}\mint{0}{1}\mint{0}{1}}
\newcommand{\dpint}{\dint{0}{1}}
\newcommand{\dpiint}{\dint{0}{1}\dint{0}{1}}
\newcommand{\dpiiint}{\dint{0}{1}\dint{0}{1}\dint{0}{1}}

// GEOMETRY //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
\newcommand{\ang}{\measuredangle}
\newcommand{\dg}{^\circ}
\newcommand{\lin}[1]{\overleftrightarrow{#
1}}
\newcommand{\ray}[1]{\overrightarrow{#
1}}
\newcommand{\seg}[1]{\overline{#
1}}
\newcommand{\sq}{\square}
\newcommand{\tr}[1]{\triangle{#
1}}

// LINEAR ALGEBRA ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
\newcommand{\Ell}{\mathcal{L}}
\newcommand{\Pol}{\mathcal{P}}
\DeclareMathOperator{\Col}{\mathrm{Col}}
\DeclareMathOperator{\Null}{Null}
\DeclareMathOperator{\rank}{\mathrm{rank}}
\DeclareMathOperator{\Row}{\mathrm{Row}}
\DeclareMathOperator{\spn}{\mathrm{span}}
// row vectors
\newcommand*{\onevec}[1]{\left[\begin{matrix}\hfill#
1\end{matrix}\right]}
\newcommand*{\twovec}[2]{\left[\begin{matrix}\hfill#
1\\\hfill#
2\end{matrix}\right]}
\newcommand*{\threevec}[3]{\left[\begin{matrix}\hfill#
1\\\hfill#
2\\\hfill#
3\end{matrix}\right]}
\newcommand*{\fourvec}[4]{\left[\begin{array}{c}#
1\\#
2\\#
3\\#
4\end{array}\right]}
\newcommand*{\fivevec}[5]{\left[\begin{matrix}\hfill#
1\\\hfill#
2\\\hfill#
3\\\hfill#
4\\\hfill#
5\end{matrix}\right]}
\newcommand*{\twovecex}[2]{\left[\begin{matrix}\hfill#
1\\[0.5ex]\hfill#
2\end{matrix}\right]}
\newcommand*{\threevecex}[3]{\left[\begin{matrix}\hfill#
1\\[0.5ex]\hfill#
2\\[0.5ex]\hfill#
3\end{matrix}\right]}
\newcommand*{\fourvecex}[4]{\left[\begin{matrix}\hfill#
1\\[0.5ex]\hfill#
2\\[0.5ex]\hfill#
3\\[0.5ex]\hfill#
4\end{matrix}\right]}
// centered column vectors
\newcommand*{\onevecc}[1]{\left[\begin{matrix}#
1\end{matrix}\right]}
\newcommand*{\twovecc}[2]{\left[\begin{matrix}#
1\\#
2\end{matrix}\right]}
\newcommand*{\threevecc}[3]{\left[\begin{matrix}#
1\\#
2\\#
3\end{matrix}\right]}
\newcommand*{\fourvecc}[4]{\left[\begin{matrix}#
1\\#
2\\#
3\\#
4\end{matrix}\right]}
\newcommand*{\fivevecc}[5]{\left[\begin{matrix}#
1\\#
2\\#
3\\#
4\\#
5\end{matrix}\right]}
\newcommand*{\threeveccex}[3]{\left[\begin{matrix}#
1\\[0.5ex]#
2\\[0.5ex]#
3\end{matrix}\right]}
\newcommand*{\fourveccex}[4]{\left[\begin{matrix}#
1\\[0.5ex]#
2\\[0.5ex]#
3\\[0.5ex]#
4\end{matrix}\right]}
// row vectors
\newcommand*{\onerowvec}[1]{\left[\begin{matrix}\hfill#
1\end{matrix}\right]}
\newcommand*{\tworowvec}[2]{\left[\begin{matrix}\hfill#
1&\hfill#
2\end{matrix}\right]}
\newcommand*{\threerowvec}[3]{\left[\begin{matrix}\hfill#
1&\hfill#
2&\hfill#
3\end{matrix}\right]}
\newcommand*{\fourrowvec}[4]{\left[\begin{matrix}\hfill#
1&\hfill#
2&\hfill#
3&\hfill#
4\end{matrix}\right]}
\newcommand*{\fiverowvec}[5]{\left[\begin{matrix}\hfill#
1&\hfill#
2&\hfill#
3&\hfill#
4&\hfill#
5\end{matrix}\right]}
// n-tuples
\newcommand*{\onetuple}[1]{(#
1)}
\newcommand*{\twotuple}[2]{(#
1,#
2)}
\newcommand*{\threetuple}[3]{(#
1,#
2,#
3)}
\newcommand*{\fourtuple}[4]{(#
1,#
2,#
3,#
4)}
\newcommand*{\fivetuple}[5]{(#
1,#
2,#
3,#
4,#
5)}
\newcommand*{\ntuple}[2]{(#
1_1,\ldots,#
1_#
2)}
\newcommand*{\listz}[2]{#
1_0,\ldots,#
1_#
2}
\newcommand*{\listo}[2]{#
1_1,\ldots,#
1_#
2}
// square numeric matrices
\newcommand*{\onebyone}[1]{\left[\begin{matrix}\hfill#
1\end{matrix}\right]}
\newcommand*{\twobytwo}[4]{\left[\begin{matrix}\hfill#
1&\hfill#
2\\\hfill#
3&\hfill#
4\end{matrix}\right]}
\newcommand*{\threebythree}[9]{\left[\begin{matrix}\hfill#
1&\hfill#
2&\hfill#
3\\\hfill#
4&\hfill#
5&\hfill#
6\\\hfill#
7&\hfill#
8&\hfill#
9\end{matrix}\right]}
// square numeric determinants
\newcommand*{\onebyonev}[1]{\begin{vmatrix}#
1\end{vmatrix}}
\newcommand*{\twobytwov}[4]{\begin{vmatrix}#
1&#
2\\#
3&#
4\end{vmatrix}}
\newcommand*{\threebythreev}[9]{\begin{vmatrix}#
1&#
2&#
3\\#
4&#
5&#
6\\#
7&#
8&#
9\end{vmatrix}}
// augmented matrices
\newcommand*{\augmented}[2]{\left[#
1\mid#
2\right]}
\newcommand*{\augtwo}[2]{\left[\begin{array}{r|r}#
1&#
2\end{array}\right]}
// \newcommand*{\augtwo}[2]{\left[#
1\mid#
2\right]}
\newcommand*{\augthree}[3]{\left[\begin{array}{r|r|r}#
1&#
2&#
3\end{array}\right]}
\newcommand*{\augfour}[4]{\left[\begin{array}{r|r|r|r}#
1&#
2&#
3&#
4\end{array}\right]}
//--------------------------------------------------------------------------
// column vectors with row separation
\newcommand*{\fourvectorchline}[4]{\left[\begin{array}{ccc}&#
1&\\[0.5ex]\hline&#
2&\\[0.5ex]\hline&#
3&\\[0.5ex]\hline&#
4\end{array}\right]}

// LOGIC ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// contradiction
\newcommand{\contra}{\ensuremath{\rightarrow\mspace{-2.5mu}\leftarrow}}
\newcommand{\ex}{\exists}
\newcommand{\nex}{\nexists}
\newcommand{\fall}{\forall}
\newcommand{\fa}{\,\forall}
// forward implication
\newcommand{\fim}{//
	\mbox{
		$\bs(//
			\mspace{-10.5mu}//
				\bs\implies//
			\mspace{-12mu}//
		\bs)$\ //
	}
}
// backward implication
\newcommand{\bim}{\mbox{$\bs(\mspace{-12mu}\bs\impliedby\mspace{-10.5mu}\bs)$\ }}
// forward inclusion
\newcommand{\fsub}{$\bs(\mspace{-5mu}\bs\subseteq\mspace{-5mu}\bs)$\ }
// backward inclusion
\newcommand{\bsub}{$\bs(\mspace{-5mu}\bs\supseteq\mspace{-5mu}\bs)$\ }
\newcommand{\sland}{\;\land\;} 		// space logical and
\newcommand{\slor}{\,\lor\, }		// space logical or
\newcommand{\tf}{\therefore\,}		// I sometimes prefer a little space after this. 

\newcommand{\bic}{\longleftrightarrow}	// biconditional
\newcommand{\varbij}{\hookrightarrow\hspace{-8pt}\rightarrow}
\newcommand{\inj}{\rightarrowtail}		// one-to-one
\newcommand{\varinj}{\hookrightarrow}
\newcommand{\onto}{\twoheadrightarrow}
\newcommand{\Ra}{\Rightarrow}		// short implies
\newcommand{\ra}{\rightarrow}		// alternate implication

\newcommand*{\mneg}[1][1]{			// my (alternative) negation
	\mspace{-5mu}// 
	\setcounter{i}{0}//
	\forloop{i}{0}{\value{i}<#
1}//
	{//
		\sim//
	}//
	\mspace{-5mu}//
}
\newcommand{\n}{\neg}				// negation
\newcommand{\s}{\sim\mspace{-5mu}}	// alternative negation
\newcommand{\vd}{\vdash}			// proves
\newcommand{\proves}{\vd}
\newcommand{\vdd}{\vDash}			// satisfies
\newcommand{\satisfies}{\vdd}

\newcommand{\nec}{\scalebox{0.75}{$\square$}}		// necessarily
\newcommand{\poss}{\scalebox{1.25}{$\diamond$}}		// possibly

\newcommand{\fA}{\mathfrak{A}}
\newcommand{\fB}{\mathfrak{B}}
\newcommand{\fN}{\mathfrak{N}}
\newcommand{\is}{\text{ is\ }}
\newcommand{\isnt}{\text{ is not\ }}
\newcommand{\IFF}{\text{ iff\ }}
\newcommand{\sbar}{\ol{s}}

// PDE ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
\newcommand{\dtau}{\,d\tau}

// Ditto marks
//====================================================================================
// ditto
\newcommand{\dit}{//
	\quad\scalebox{0.75}{\ensuremath{\prime\prime}}\quad//
}
\newcommand{\ditt}{\dit\dit}
\newcommand{\dittt}{\ditt\dit}
\newcommand{\ditttt}{\ditt\ditt}
// sparse ditto
\newcommand{\sdit}{\qquad\scalebox{0.75}{\ensuremath{\prime\prime}}\qquad}
\newcommand{\sditt}{\sdit\sdit}
\newcommand{\sdittt}{\sditt\sdit}
\newcommand{\sditttt}{\sditt\sditt}
// dense ditto
\newcommand{\ddit}{$\mspace{9mu}$\scalebox{0.75}{\ensuremath{\prime\prime}}$\mspace{9mu}$}
\newcommand{\dditt}{\ddit\ddit}
\newcommand{\ddittt}{\dditt\ddit}
\newcommand{\dditttt}{\dditt\dditt}

// For loops
\newcounter{i}
\newcommand*{\mdit}[1][1]{// my ditto; invoked as \mdit[n] for n ditto marks
	\forloop{i}{0}{\value{i} < #
1}//
	{//
		\dit//
	}//
}
// my sparse ditto
\newcommand*{\msdit}[1][1]{\forloop{i}{0}{\value{i}<#
1}{\sdit}}
// my dense ditto
\newcommand*{\mddit}[1][1]{\forloop{i}{0}{\value{i}<#
1}{\ddit}}

// VERSE ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
\newcommand{\inp}[1]{\newpage\input{#
1}}

\newcommand{\attrib}[1]{\nopagebreak{\raggedleft\footnotesize#
1\par}\ }

\newcommand{\gap}[1][1]{\hspace{#
1\vgap}}
\newcommand{\ngap}[1][1]{\hspace{-#
1\vgap}}

\newcommand{\nin}{\hspace{-\vgap}}
\newcommand{\bin}{\hspace{2\vgap}}
\newcommand{\tin}{\hspace{3\vgap}}
\newcommand{\fin}{\hspace{4\vgap}}
\newcommand{\cin}{\hspace{5\vgap}}
\newcommand{\nbin}{\hspace{-2\vgap}}
\newcommand{\ntin}{\hspace{-3\vgap}}
\newcommand{\nfin}{\hspace{-4\vgap}}
\newcommand{\ncin}{\hspace{-5\vgap}}
\newcommand{\hin}{\hspace{0.5\vgap}}
\newcommand{\nhin}{\hspace{-0.5\vgap}}

\newcommand{\dinkus}{
	\begin{center}
		\textbf{***}\vspace{-0.25cm}
	\end{center}
}

\newtotcounter{numSS}
\newtotcounter{numM}
\newcommand{\adds}{
	\addtocounter{numSS}{1}
	\addtocounter{total}{1}
}
\newcommand{\addm}{
	\addtocounter{numM}{1}
	\addtocounter{total}{1}
}
\newcounter{piecewise}
\newcommand{\addp}{//
	(\thepiecewise)
	\addtocounter{piecewise}{1}
}
\newcounter{reading}
\newcommand{\addr}{//
	(\thereading)
	\addtocounter{reading}{1}
}
\newcounter{stem}
\newcommand{\addstem}{//
	(\thestem)
	\addtocounter{stem}{1}
}
\newcommand{\push}{\vfill\null\columnbreak}
?>