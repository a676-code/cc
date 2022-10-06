/**************************
Title: Polynomial.h
Author: Andrew Lounsbury
Date: 6/2/2020
Purpose: for holding EU's
**************************/

/***************************************
1) create nT zero terms in nV variables
2) read in coeff and expo for each term
3) remove zero terms
4) add like terms
***************************************/

#ifndef POLYNOMIAL_H
#define POLYNOMIAL_H
#include "inputValidation.cpp"
#include "term.h"

#include <iostream>
#include <cmath>
#include <vector>
using namespace std;

class Polynomial
{
	private:
		Term* leading;
		Term* trailing;
		int numTerms;
		int numVariables;
		int totalDegree;
		vector<vector<Polynomial*> > derivatives;
		bool linear;
	public:		
		double getCoefficient(int i) const 				{ return getTerm(i)->coefficient; }
		vector<vector<Polynomial*> > getDerivatives() 	{ return derivatives; }
		int getExponent(int t, int var) const 			{ return getExponents(t).at(var); }
		vector<int> getExponents(int i) const 			{ return this->getTerm(i)->exponents; }
		int getNumTerms() const 						{ return numTerms; }
		int getNumVariables() const 					{ return numVariables; }
		int getSizeDerivatives() 						{ return derivatives.size(); }
		// with respect to v
		int getSizeDerivativesWRT(int v) 				{ return derivatives.at(v).size(); }
		int getTotalDegree() const 						{ return totalDegree; }
		Term* getTrailing() const 						{ return trailing; }
		void setCoefficient(int t, double num) 			{ getTerm(t)->coefficient = num; }
		void setExponent(int t, int v, int num) 		{ getTerm(t)->exponents.at(v) = num; }
		void setExponents(int t, vector<int> expo) 		{ getTerm(t)->exponents = expo; }
		void setNext(int t, Term* term) 				{ getTerm(t)->next = term;}
		void setNumTerms(int num) 						{ numTerms = num; }
		void setTotalDegree(int num) 					{ totalDegree = num; }
		
		Polynomial();
		Polynomial(int, int, int);
		Polynomial(int, int, vector<int>, int);
		~Polynomial();
		
		// Polynomial operator+(const Polynomial &p);
		// Polynomial operator+(Polynomial &l, Polynomial &r);
		Polynomial operator*(const Polynomial &p);
		Polynomial* operator-(const Polynomial &p);
		bool operator==(const Polynomial &p);
		bool operator!=(const Polynomial &p);
		bool operator<(const Polynomial &p);
		bool operator>(const Polynomial &p);
		
		void addTerm(int, vector<int>);
		void addTerm(Term*);
		void computeTotalDegree();
		Polynomial* derivative(int);
		void enterInfo();
		double eval(double);
		Polynomial* getDerivative(int, int);
		int getNonZeroExpo(int) const;
		Term* getTerm(int) const;
		void insertTerm(int, vector<int>, int);
		void insertTerm(int, Term*);
		Polynomial* integrate(int);
		double integrateOverInterval(double, double, int);
		bool isConstant();
		bool isConstantTerm(int);
		bool isLinear();
		void lexOrder();
		void printPolynomial();
		void printPolynomial(int, int);
		void removeTerm(int &);
		void setEUCoefficients(vector<int>, int);
		void setEUExponents(vector<vector<int> >);
		void setTerm(int, Term*);
		void simplify();
};

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

Polynomial::Polynomial() // FINISH
{
	leading = NULL;
	trailing = NULL;
	
	numTerms = -1;
	numVariables = -1;
	totalDegree = -1;
	
	linear = true;
}

// these polynomials are in R[var]
// a_{nT}x^{nT} + a_{nT-1}x^{nT-1} + ... + a_1x^{nT-(nT-1)} + a_0
Polynomial::Polynomial(int nT, int nV, int var) // FINISH
{
	numVariables = nV;
	
	if (nT == -1 || var == -1) // default is 1x_{var}
	{		
		numTerms = 1;
		
		leading = new Term;
		leading->coefficient = 1;
		
		leading->exponents = vector<int>(numVariables); // numVariables is numPlayers
		for (int i = 0; i < numVariables; i++)
		{
			if (i == var)
				leading->exponents.at(i) = 1;
		}
		leading->next = NULL;
		
		trailing = new Term;
		trailing = leading;
		
		totalDegree = 1;
	}
	else
	{
		numTerms = nT;
		
		leading = new Term;
		leading->coefficient = 0;
		leading->exponents.resize(numVariables);
		for (int v = 0; v < numVariables; v++)
		{
			if (v == var)
				leading->exponents.at(v) = numTerms - 1;
			else
				leading->exponents.at(v) = 0;
		}
		leading->next = NULL;
		
		if (numTerms == 1)
			trailing = leading;
		else if (numTerms == 2)
		{			
			trailing = new Term;
			trailing->coefficient = 0;
			trailing->exponents.resize(numVariables);
			for (int v = 0; v < numVariables; v++)
				trailing->exponents.at(v) = 0;
			trailing->next = NULL;
			leading->next = trailing;		
		}
		else // numTerms >= 3
		{
			Term* temp = new Term;
			Term* term2 = new Term;
			term2->coefficient = 0;
			term2->exponents.resize(numVariables);
			for (int v = 0; v < numVariables; v++)
			{
				if (v == var)
					term2->exponents.at(v) = numTerms - 2;
				else
					term2->exponents.at(v) = 0;
			}
			leading->next = term2;
			
			temp = term2;
			for (int t = 2; t < numTerms - 1; t++) // terms 2,..., numTerms - 1
			{
				Term* newTerm = new Term;
				newTerm->coefficient = 0;
				newTerm->exponents.resize(numVariables);
				for (int v = 0; v < numVariables; v++)
				{
					if (v == var)
						newTerm->exponents.at(v) = numTerms - (t + 1);
					else
						newTerm->exponents.at(v) = 0;
				}
				temp->next = newTerm; // term t - 1 next points to term t
				temp = newTerm; // temp moves up one term
			}
			
			trailing = new Term;
			temp->next = trailing;
			trailing->coefficient = 0;
			trailing->exponents.resize(numVariables);
			for (int v = 0; v < numVariables; v++)
				trailing->exponents.at(v) = 0;
			trailing->next = NULL;
		}
		totalDegree = -1;
	}
	linear = true;
}

Polynomial::~Polynomial()
{
	Term* curTerm;
	
	curTerm = leading;
	while (curTerm)
	{
		delete curTerm;
		curTerm = curTerm->next;
	}
	delete this;
}

/*Polynomial Polynomial::operator+(const Polynomial &p)
{		
	Polynomial poly;
	// int pTermIndex = -1, thisTermIndex = -1;
	// bool inP = false, inThis = false;
	
	// constructor creates a single NULL node; if so, do nothing
	if (this == NULL)
		return p;
	else if (&p == NULL || (this == NULL && &p == NULL))
		return *this;
	
	/*if (this->getNumTerms() >= p.getNumTerms())
	{		
		// start with highest degree/first term in this
		for (int t1 = 0; t1 < this->getNumTerms(); t1++)
		{					
			inP = false;
			// check if p has a term of the same degree
			for (int t2 = 0; t2 < p.getNumTerms(); t2++)
			{						
				if (this->getExponents(t1) == p.getExponents(t2))
				{							
					inP = true;
					pTermIndex = t2;
				}
			}
			
			if (inP) // add coefficients
			{
				poly.setCoefficient(t1, this->getCoefficient(t1) + p.getCoefficient(pTermIndex));
				poly.setExponents(t1, this->getExponents(t1));
			}
			else
			{
				poly.setCoefficient(t1, this->getCoefficient(t1));
				poly.setExponents(t1, this->getExponents(t1));
			}
		}
	}
	else
	{		
		// start with highest degree/first term in p
		for (int t1 = 0; t1 < p.getNumTerms(); t1++)
		{
			inThis = false;
			// check if this has a term of the same degree
			for (int t2 = 0; t2 < this->getNumTerms(); t2++)
			{
				if (this->getExponents(t2) == p.getExponents(t1))
				{
					inThis = true;
					thisTermIndex = t2;
				}
			}
			
			if (inThis) // add coefficients
			{
				poly.setCoefficient(t1, this->getCoefficient(thisTermIndex) + p.getCoefficient(t1));
				poly.setExponents(t1, p.getExponents(t1));
			}
			else
			{
				poly.setCoefficient(t1, p.getCoefficient(t1));
				poly.setExponents(t1, p.getExponents(t1));
			}
		}				
	}*/
	
	/*cout << "\nNT: " << this->getNumTerms() << endl;
	cout << "NT: " << p.getNumTerms() << endl;
	
	for (int t = 0; t < this->getNumTerms(); t++)
		poly.addTerm(this->getTerm(t));
	for (int t = 0; t < p.getNumTerms(); t++)
		poly.addTerm(p.getTerm(t));
	
	poly.simplify();
	return poly;
}*/

Polynomial Polynomial::operator*(const Polynomial &p)
{
	int nV = this->getNumVariables();
	if (nV < p.getNumVariables())
		nV = p.getNumVariables();
				
	Polynomial* poly = new Polynomial(this->getNumTerms() * p.getNumVariables(), nV, -1);
	
	for (int t1 = 0; t1 < this->getNumTerms(); t1++)
	{
		for (int t2 = 0; t2 < p.getNumTerms(); t2++)
		{
			poly->setCoefficient(t1 + t2, this->getCoefficient(t1) + p.getCoefficient(t2));
			for (int v = 0; v < poly->getNumVariables(); v++)
				poly->setExponent(t1 + t2, v, this->getExponent(t1, v) + p.getExponent(t2, v));
		}
	}
	return *poly;
}

Polynomial* Polynomial::operator-(const Polynomial &p)
{
	// cout << "SUBTRACTION\n";
	
	Polynomial* poly;
	int pTermIndex = -1, thisTermIndex = -1;
	bool inP = false, inThis = false;
	
	if (this->getNumTerms() >= p.getNumTerms())
	{
		poly = new Polynomial(this->getNumTerms(), this->getNumVariables(), -1); // -1 bc the var parameter isn't needed and is irrelevant
		
		// start with highest degree/first term in this
		for (int i = 0; i < this->getNumTerms(); i++)
		{
			inP = false;
			// check if p has a term of the same degree
			for (int j = 0; j < p.getNumTerms(); j++)
			{
				if (p.getExponents(j) == this->getExponents(i))
				{
					inP = true;
					pTermIndex = j;
				}
			}
			
			if (inP) // subtract coefficients
			{
				poly->setCoefficient(i, this->getCoefficient(i) - p.getCoefficient(pTermIndex));
				poly->setExponents(i, this->getExponents(i));
			}
			else
			{
				poly->setCoefficient(i, this->getCoefficient(i));
				poly->setExponents(i, this->getExponents(i));
			}
		}
	}
	else
	{
		poly = new Polynomial(p.getNumTerms(), p.getNumVariables(), -1); // -1 bc the var parameter isn't needed and is irrelevant
		
		// start with highest degree/first term in p
		for (int i = 0; i < p.getNumTerms(); i++)
		{
			inThis = false;
			// check if this has a term of the same degree
			for (int j = 0; j < this->getNumTerms(); j++)
			{
				if (this->getExponents(j) == p.getExponents(i))
				{
					inThis = true;
					thisTermIndex = j;
				}
			}
			
			if (inThis) // subtract coefficients
			{
				poly->setCoefficient(i, this->getCoefficient(thisTermIndex) - p.getCoefficient(i));
				poly->setExponents(i, p.getExponents(i));
			}
			else
			{
				poly->setCoefficient(i, -p.getCoefficient(i));
				poly->setExponents(i, p.getExponents(i));
			}
		}				
	}
	poly->simplify();			
	return poly;
}

bool Polynomial::operator==(const Polynomial &p)
{
	if (this->getTotalDegree() != p.getTotalDegree() || this->getNumTerms() != p.getNumTerms())
		return false;
	
	for (int i = 0; i < p.getNumTerms(); i++)
	{
		if (this->getCoefficient(i) != p.getCoefficient(i))// || this->getExponents(i) != p.getExponents(i))
			return false;
		
		if (this->getExponents(i) != p.getExponents(i))
			return false;
	}
	return true;
}

bool Polynomial::operator!=(const Polynomial &p)
{
	if (*this == p)
		return false;
	else
		return true;
}

bool Polynomial::operator<(const Polynomial &p) // FINISH
{
	// x^alpha = a_0x^alpha_0 + a_1x^alpha_1 + ... + a_{nT-2}x^alpha_{nT-2} + a_{nT-1}x^alpha_{nT-1}
	// x^beta = b_0x^beta_0 + b_1x^beta_1 + ... + b_{nT-2}x^beta_{nT-2} + b_{nT-1}x^beta_{nT-1}
	
	// constructor creates a single NULL node; if so, do nothing
	if (this == NULL && &p != NULL && p.getCoefficient(0) != 0)
		return true;
	else if (this != NULL && &p == NULL)
		return false;
	else if (this == NULL && &p == NULL)
		return false;
	
	// check to see if equal terms exist
	
	if (*this == p)
		return false;
	
	// eliminating equal terms
	// if p = f + h and q = g + h ==> only need to check f < g

	// checking integral
	if ((*this - p)->integrateOverInterval(0, 1, 0) <= 0)
		return false;
	
	// check derivatives
	
	return true;
}

bool Polynomial::operator>(const Polynomial &p)
{
	if (*this == p || *this < p)
		return false;
	else
		return true;
}

void Polynomial::addTerm(int coeff, vector<int> expo) // TEST
{
	cout << "ADDTERM other\n";
	
	int n = 0, numExponents = 0;
	while ((unsigned)n < expo.size() && numExponents < 2)
	{
		if (expo.at(n) != 0)
			numExponents++;
		n++;
	}
	if (linear && numExponents > 1)
		linear = false;
	
	Term* newTerm = new Term;
	newTerm->coefficient = coeff;
	newTerm->exponents = expo;
	
	if (!leading)
	{
		cout << "leading == NULL\n";
		leading = newTerm;
		trailing = newTerm;
	}
	else
	{
		cout << "ELSE\n";
		trailing->next = newTerm;
		cout << "test1\n";
		trailing = newTerm;
		cout << "test2\n";
	}
	numTerms++;
}

void Polynomial::addTerm(Term* term) // TEST
{
	cout << "ADDTERM\n";
	
	// Check if new term is nonlinear
	if (linear)
	{
		int n = 0, numExponents = 0;
		while ((unsigned)n < term->exponents.size() && numExponents < 2)
		{
			if (term->exponents.at(n) != 0)
				numExponents++;
			n++;
		}
		if (linear && numExponents > 1)
			linear = false;
	}
	
	if (!leading)
	{
		leading = term;
		trailing = term;
	}
	else
	{
		trailing->next = term;
		trailing = term;
	}
	numTerms++;
	
	cout << "END ADDTERM: ";
	this->printPolynomial();
	cout << endl;
}

void Polynomial::computeTotalDegree()
{
	int sum = 0, max = 0;
	for (int t = 0; t < numTerms; t++)
	{
		sum = 0;
		for (int v = 0; v < numVariables; v++)
			sum += getTerm(t)->exponents.at(v);
		
		if (sum > max)
			max = sum;
	}
	setTotalDegree(max);
}

Polynomial* Polynomial::derivative(int var) // FINISH: linear derivatives from nonlinear polynomials
{
	// computes the derivative of this wrt to the var-th variable
	
	Polynomial* poly = new Polynomial(this->getNumTerms(), this->getNumVariables(), 0);
	for (int t = 0; t < poly->getNumTerms(); t++)
	{
		poly->setCoefficient(t, this->getCoefficient(t));
		for (int v = 0; v < poly->getNumVariables(); v++)
			poly->setExponent(t, v, this->getExponent(t, v));
	}
	
	for (int t = 0; t < poly->getNumTerms(); t++)
	{
		if (poly->getExponent(t, var) != 0) // var-th variable occurs
		{			
			poly->setCoefficient(t, poly->getCoefficient(t) * poly->getExponent(t, var));
			
			for (int v = 0; v < poly->getNumVariables(); v++)
			{
				if (v != var) // if nonlinear, this is taking the partial
					poly->setExponent(t, v, 0);
				else
					poly->setExponent(t, v, poly->getExponent(t, v) - 1);
			}
		}
		else // var-th variable does not occur
		{
			if (numTerms > 1)
				poly->removeTerm(t);
			else
				poly->setCoefficient(t, 0); // is now the zero polynomial
		}
	}
	// poly->printPolynomial();
	return poly;
}

/* separate bc mixed strategies doesn't 
require the user to enter info */
void Polynomial::enterInfo()
{
	double c = 0.0;
	int e = -1;
	
	for (int t = 0; t < numTerms; t++)
	{
		cout << "-------------------------------------------" << endl;
		if(t == 0)
			cout << "Enter the 1st coefficient:  ";
		else if (t == 1)
			cout << "Enter the 2nd coefficient:  ";
		else if (t == 2)
			cout << "Enter the 3rd coefficient:  ";
		else
			cout << "Enter the " << t + 1 << "-th coefficient:  ";
		cin >> c;
		validateTypeDouble(c);
		this->setCoefficient(t, c);
		
		for (int v = 0; v < numVariables; v++)
		{
			if (t == 0)
			{
				cout << "Enter the ";
				if (v == 0)
					cout << "1st ";
				else if (v == 1)
					cout << "2nd ";
				else if (v == 2)
					cout << "3rd ";
				else
					cout << v + 1 << "-th ";
				cout << "exponent in the 1st term:  ";
			}
			else if (t == 1)
			{
				cout << "Enter the ";
				if (v == 0)
					cout << "1st ";
				else if (v == 1)
					cout << "2nd ";
				else if (v == 2)
					cout << "3rd ";
				else
					cout << v + 1 << "-th ";
				cout << "exponent in the 2nd term:  ";
			}
			else if (t == 2)
			{
				cout << "Enter the ";
				if (v == 0)
					cout << "1st ";
				else if (v == 1)
					cout << "2nd ";
				else if (v == 2)
					cout << "3rd ";
				else
					cout << v + 1 << "-th ";
				cout << "exponent in the 3rd term:  ";
			}
			else
			{
				cout << "Enter the ";
				if (v == 0)
					cout << "1st ";
				else if (v == 1)
					cout << "2nd ";
				else if (v == 2)
					cout << "3rd ";
				else
					cout << v + 1 << "-th ";
				cout << "exponent in the " << t + 1 << " term:  ";
			}
			cin >> e;
			validateTypeInt(e);
			this->setExponent(t, v, e);			
		}
	}
	computeTotalDegree();
}

double Polynomial::eval(double val)
{
	double num = 1.0, sum = 0.0;
	
	if (isConstant())
		return getCoefficient(0);
	else
	{
		for(int t = 0; t < numTerms; t++)
		{ 
			num = getCoefficient(t);
			for (int v = 0; v < numVariables; v++)
				num *= pow(val, getExponent(t, v));
			
			sum += num;
		}
		return sum;
	}
}

Polynomial* Polynomial::getDerivative(int n, int v)
{
	bool loop = false;
	
	// computes derivative up to the (n + 1)-th derivative wrt the v-th variable
	
	// Resizing derivatives
	if (getSizeDerivatives() < v + 1)
		derivatives.resize(v + 1);
	
	if (derivatives.at(v).size() == 0)
	{
		derivatives.at(v).resize(1);
		derivatives.at(v).at(0) = this;
		loop = true;
	}
	else if (derivatives.at(v).at(0) != this)
	{
		derivatives.at(v).at(0) = this;
		loop = true;
	}
	
	// Pushing into derivatives.at(v) until it has spots 0,...,n
	cout << "size: " << getSizeDerivativesWRT(v) << endl;
	cout << "n + 1: " << n + 1 << endl;
	while (getSizeDerivativesWRT(v) < n + 1 && loop)
	{
		cout << "size: " << getSizeDerivativesWRT(v) << endl;
		
		if (getSizeDerivativesWRT(v) == 0)
			derivatives.at(v).push_back(derivative(v));
		else
			derivatives.at(v).push_back(getDerivative(derivatives.at(v).size() - 1, v)->derivative(v));
	}
	cout << "size1: " << derivatives.size() << endl;
	cout << "size2: " << derivatives.at(v).size() << endl;
	return derivatives.at(v).at(n);
}

// for linear polynomials
int Polynomial::getNonZeroExpo(int t) const
{ 
	for (int v = 0; v < numVariables; v++)
	{
		if (this->getExponent(t, v) != 0)
			return v;
	}
	return (numTerms - 1);
}

Term* Polynomial::getTerm(int t) const
{
	int count = 0;
	Term* curTerm;
	curTerm = leading;
	
	while (count < t)
	{
		curTerm = curTerm->next;
		count++;
	}
	return curTerm;
}

void Polynomial::insertTerm(int t, vector<int> expo, int coeff)
{
	Term* newTerm = new Term;
	
	// putting info into newTerm
	newTerm->coefficient = coeff;
	for (int n = 0; (unsigned)n < expo.size(); n++)
		newTerm->exponents.push_back(expo.at(n));
	
	if (t == 0)
	{
		newTerm->next = leading->next;
		leading = newTerm;
	}
	else
	{
		newTerm->next = getTerm(t - 1)->next; // t - 1 --> new --> t
		getTerm(t - 1)->next = newTerm; // insert at position i
	}
	numTerms++;
}

void Polynomial::insertTerm(int t, Term* term)
{	
	if (t == 0)
	{
		term->next = leading->next;
		leading = term;
	}
	else
	{
		term->next = getTerm(t - 1)->next; // i - 1 --> new --> i
		getTerm(t - 1)->next = term; // insert at position i
	}
	numTerms++;
}

Polynomial* Polynomial::integrate(int var) // FINISH (... + c)
{
	Polynomial* poly = new Polynomial(getNumTerms(), getNumVariables(), 0);
	
	// copying this pointer
	for (int t = 0; t < numTerms; t++)
	{
		for (int v = 0; v < poly->getNumVariables(); v++)
		{
			poly->setCoefficient(t, getCoefficient(t));
			poly->setExponent(t, v, getExponent(t, v));
		}
	}
	// computing
	for (int t = 0; t < numTerms; t++)
	{
		poly->setExponent(t, var, poly->getExponent(t, var) + 1);
		poly->setCoefficient(t, poly->getCoefficient(t) / static_cast<double>(poly->getExponent(t, var)));
	}
	return poly;
}

double Polynomial::integrateOverInterval(double a, double b, int var)
{
	// cout << "INTEGRATEOVER\n";
	
	double num = -1;
	Polynomial* poly = new Polynomial(getNumTerms(), getNumVariables(), 0);
	
	for (int t = 0; t < poly->getNumTerms(); t++)
	{
		for (int v = 0; v < poly->getNumVariables(); v++)
		{
			poly->setCoefficient(t, getCoefficient(t));
			poly->setExponent(t, v, getExponent(t, v));
		}
	}
	poly = poly->integrate(var);
	num = poly->eval(b) - poly->eval(a);
	
	cout << endl;
	
	// cout << "num:  " << num << endl;
	// cout << "end INTEGRATEOVER\n";
	return num;
}

bool Polynomial::isConstant()
{
	bool allExpoZero = true;
	for (int t = 0; t < numTerms; t++)
	{
		if (!isConstantTerm(t))
		{
			allExpoZero = false;
			return allExpoZero;
		}
	}
	return allExpoZero;
}

bool Polynomial::isConstantTerm(int t)
{
	bool allExpoZero = true;
	for (int v = 0; v < numVariables; v++)
	{
		if(getExponent(t, v) != 0)
			allExpoZero = false;
	}
	return allExpoZero;
}

bool Polynomial::isLinear() // TEST
{
	int numExponents = 0;
	for (int t = 0; t < numTerms; t++)
	{
		numExponents = 0;
		for (int v = 0; v < numVariables; v++)
		{
			if (getExponent(t, v) != 0)
				numExponents++;
			if (numExponents > 1)
				return false;
		}
	}
	return true;
}

void Polynomial::lexOrder()
{
	// LME nonzero entry of alpha-beta = (a_1 - b_1, ... , a_n - b_n) is positive
	
	Term* temp = new Term;
	
	for (int t1 = 0; t1 < getNumTerms(); t1++)
	{
		for (int t2 = t1 + 1; t2 < getNumTerms(); t2++)
		{
			for (int var = 0; var < getNumVariables(); var++)
			{
				if (getExponent(t1, var) - getExponent(t2, var) < 0) // switch terms t1 and t2
				{
					temp->coefficient = getCoefficient(t2);
					temp->exponents = getExponents(t2);
					
					setTerm(t2, getTerm(t1));
					setTerm(t1, temp);
				}
			}
		}
	}
}

void Polynomial::printPolynomial()
{
	bool nonConstant = false, oneMoreNonZero = false;
	int count = 0;
	
	// simplify();
	for (int t = 0; t < numTerms; t++) // terms
	{
		nonConstant = false;
		for (int v = 0; v < numVariables; v++) // variables
		{
			if (getExponent(t, v) != 0)
				nonConstant = true; // at least one is nonzero
		}
		
		oneMoreNonZero = false;
		count = t + 1;
		if (t < numTerms - 1) // checks if there's one more nonzero term
		{
			while (!oneMoreNonZero && count != numTerms)
			{
				if (getCoefficient(count) != 0)
					oneMoreNonZero = true;
				
				count++;
			}
		}
		
		if (!leading) // empty
			cout << "EMPTY POLYNOMIAL: enter values for coefficients and exponents";
		else // not empty
		{
			if (getCoefficient(t) != 0) // nonzero coefficient
			{
				if (t == 0 && getCoefficient(t) < 0) // first term negative
				{
					if ((getCoefficient(t) != -1 && nonConstant) || !nonConstant)
						cout << getCoefficient(t);
					else
						cout << "-";
				}
				else // not first term OR nonnegative coefficient
				{
					if ((abs(getCoefficient(t)) != 1 && nonConstant) || !nonConstant)
						cout << abs(getCoefficient(t));
				}
				
				for (int v = 0; v < numVariables; v++)
				{
					if (getExponent(t, v) != 0)
					{
							cout << "x";
							if (numVariables > 1)
								cout << "_" << v + 1;
							if (getExponent(t, v) != 1)
								cout << "^" << getExponent(t, v);
					}
				}
				
				if (t < numTerms - 1 && oneMoreNonZero && getCoefficient(t + 1) >= 0) // && exists(a_k)[i < k <= n && a_k != 0]
					cout << " + ";
				else if (t < numTerms - 1 && oneMoreNonZero && getCoefficient(t + 1) < 0)
					cout << " - ";
			}
			else if (getCoefficient(t) == 0) // zero coefficient
			{
				if (numTerms == 1) // in a monomial
					cout << "0";
			}
		}
	}
	cout << endl;
}

void Polynomial::printPolynomial(int player, int strat) // For EU's
{
	bool nonConstant = false, oneMoreNonZero = false;
	int count = 0;
	
	simplify();
	for (int t = 0; t < numTerms; t++) // terms
	{
		nonConstant = false;
		for (int v = 0; v < numVariables; v++) // variables
		{
			if (getExponent(t, v) != 0)
				nonConstant = true; // at least one is nonzero
		}
		
		oneMoreNonZero = false;
		count = t + 1;
		if (t < numTerms - 1) // checks if there's one more nonzero term
		{
			while (!oneMoreNonZero && count != numTerms)
			{
				if (getCoefficient(count) != 0)
					oneMoreNonZero = true;
				
				count++;
			}
		}
		
		if (!leading) // empty
			cout << "EMPTY POLYNOMIAL: enter values for coefficients and exponents";
		else // not empty
		{
			if (getCoefficient(t) != 0) // nonzero coefficient
			{
				if (t == 0 && getCoefficient(t) < 0) // first term negative
				{
					if ((getCoefficient(t) != -1 && nonConstant) || !nonConstant)
						cout << getCoefficient(t);
					else
						cout << "-";
				}
				else // not first term OR nonnegative coefficient
				{
					if ((abs(getCoefficient(t)) != 1 && nonConstant) || !nonConstant)
						cout << abs(getCoefficient(t));
				}
				
				for (int v = 0; v < numVariables; v++)
				{
					if (getExponent(t, v) != 0)
					{
							cout << "p_" << player << ", " << strat;
							if (getExponent(t, v) != 1)
								cout << "^" << getExponent(t, v);
					}
				}
				
				if (t < numTerms - 1 && oneMoreNonZero && getCoefficient(t + 1) >= 0) // && exists(a_k)[i < k <= n && a_k != 0]
					cout << " + ";
				else if (t < numTerms - 1 && oneMoreNonZero && getCoefficient(t + 1) < 0)
					cout << " - ";
			}
			else if (getCoefficient(t) == 0) // zero coefficient
			{
				if (numTerms == 1) // in a monomial
					cout << "0";
			}
		}
	}
	cout << endl;
}

void Polynomial::removeTerm(int &t)
{	
	int count = 0;
	Term* curTerm;
	Term* previousTerm;
	
	if (t == 0)
	{
		delete leading;
		leading = leading->next;
	}
	else
	{
		curTerm = leading;
		while (count < t)
		{
			previousTerm = curTerm;
			curTerm = curTerm->next;
			count++;
		}
		previousTerm->next = curTerm->next;
		delete curTerm;
		curTerm = curTerm->next;
	}
	numTerms--;
	t--;
}

void Polynomial::setEUCoefficients(vector<int> coeffs, int numPlayers) // TEST
{
	// enters coeffs into the expected utility, leaving the last term as a_{n-1}
	
	// if (numPlayers < 3)
	// {
		/*
		nP == 2:
		--------
		n = numTerms
		a_0p_0 + ... + a_{n-2}p_{n-2} + a_{n-1}(1 - p_0 - ... - p_{n-2})
		a_0p_0 + ... + a_{n-2}p_{n-2} + a_{n-1} - a_{n-1}p_0 - ... - a_{n-1}p_{n-2}
		(a_0p_0 - a_{n-1}p_0) + ... + (a_{n-2}p_{n-2} - a_{n-1}p_{n-2}) + a_{n-1}
		(a_0 - a_{n-1})p_0 + ... + (a_{n-2} - a_{n-1})p_{n-2} + a_{n-1}

		0p + 3(1-p)
		0p + 3 - 3p
		0p - 3p + 3
		(0 - 3)p + 3
		-3p + 3
		*/
			
		// (a_i - a_{n-1})p_i
		for (int t = 0; t < numTerms - 1; t++)
		{
			this->setCoefficient(t, coeffs.at(t) - coeffs.at(numTerms - 1));
			for (int v = 0; v < numVariables; v++)
			{
				if (t == v)
					this->setExponent(t, v, 1);
				else
					this->setExponent(t, v, 0);
			}
		}
		// a_{n-1}
		this->setCoefficient(numTerms - 1, coeffs.at(numTerms - 1));
		for (int v = 0; v < numVariables; v++)
			this->setExponent(numTerms - 1, v, 0);
	// }
	// else
		// cout << "ERROR: numPLayers > 2\n";
}

void Polynomial::setEUExponents(vector<vector<int> > exponents) // FINISH: need number of terms per polynomial
{
	// enters exponents into the expected utility
	
	// for (int t = 0; t < numTerms; t++)
		// this->setTerm(t, exponents.at(t));
}

void Polynomial::setTerm(int t, Term* temp)
{
	this->setCoefficient(t, temp->coefficient);
	this->setExponents(t, temp->exponents);
}

void Polynomial::simplify()
{
	cout << "SIMPLIFY\n";
	
	bool sameAlpha = true;
	
	// getting rid of zero terms
	for (int t = 0; t < numTerms; t++)
	{
		cout << "t: " << t << endl;
		if (getCoefficient(t) == 0 && numTerms > 1)
		{
			// cout << "IF\n";
			removeTerm(t);
		}
	}
	
	// combining nonzero terms
	for (int t1 = 0; t1 < numTerms; t1++)
	{
		cout << "t1: " << t1 << endl;
		for (int t2 = t1 + 1; t2 < numTerms; t2++)
		{
			cout << "t2: " << t2 << endl;
			sameAlpha = true;
			for (int v = 0; v < numVariables; v++)
			{
				if (getTerm(t1)->exponents.at(v) != getTerm(t2)->exponents.at(v))
					sameAlpha = false;
			}
			if (sameAlpha)
			{
				cout << "\tsame\n";
				setCoefficient(t1, getCoefficient(t1) + getCoefficient(t2));
				if (numTerms > 1)
					removeTerm(t2);
			}
		}
	}
	
	cout << "DONE SIMPLIFY\n";
}

#endif