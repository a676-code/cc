#include <iostream>
#include <algorithm>
#include <array>
#include <iterator>
#include <utility>
#include <vector>
#include <fstream>
#include <typeinfo>
#include <string>
#include <cstdio> // \u221E
#include <windows.h>
using namespace std;

vector<int> chain; // outputs up to the end of a loop
vector<int> loop; // a subvector of chain
ifstream inFile;
ofstream outFile;
int returnOverflow = -1;
int x = 0;
bool growsOnChain(int x);
bool chainsIntersect(int x, int y);
bool consecutivePairExists(int x);
bool doesLoop(int x);
vector<int> f(int x, int call = 0);
vector<int> f_inv(int x);
vector<int> f_print(int x, int call = 0);
vector<int> getChain(int x, int call = 0);
vector<int> getLayer(int n);
vector<vector<int> > getLayers(int n);
vector<vector<int> > getLoops(int max);
int getNumDrops(int x);
int getNumInLayer(int n);
int getNumJumps(int x);
bool growsAt(int x);
bool growsOver(int n, int m);
bool intersectOffMainChannel(int x, int y);
vector<int> intersectv(vector<int> v1, vector<int> v2);
bool isInChain(int x, int y);
bool isInChain(int x, vector<int> chain);
bool isOverflowAdd(int x, int y);
bool isOverflowMultiply(int x, int y);
bool isPowerOf2(int x);
bool loopsAt(int x);
bool loopsOver(int n, int m);
int posInChain(int x, int y);
void printv(vector<int> v);
void printv2d(vector<vector<int> > v);
bool separatedPairExists(int x, int s);
bool stabilizesAt(int x);
bool stabilizesOver(int n, int m);
void writeStatus(int a, int b, int max);
void writev(vector<int> v);
int oddCoefficient = 5;
int oddAddend = 13;
int evenDivisor = 2;
int originalOddCoefficient = oddCoefficient;
int originalOddAddend = oddAddend;
int originalEvenDivisor = evenDivisor;

int main()
{
    SetConsoleOutputCP(CP_UTF8);
    cout << "ax + b\n";
    cout << "(a, b):\t(e, e) -> e [only becomes odd after stripping factors of c from x]\n";
    cout << "\t(e, o) -> o [-> \u221E]\n";
    cout << "\t(o, e) -> o if x is o[-> \u221E], e if x is e\n";
    cout << "\t(o, o) -> e if x is o, o if x is e [CC]\n";
    cout << "x / c\t\"x / c if c divides x\"\n";
    cout << "(a, b, c) = (" << oddCoefficient << ", " << oddAddend << ", " << evenDivisor << ")\n";

    while (x == 0)
    {
        cout << "Enter a value for x: ";
        cin >> x;
    }
    f_print(x);

    /*
    int max = 20;
    for (int a = 1; a < max; a++)
    {
        for (int b = 1; b < max; b++)
            writeStatus(a, b, 1000);
    }
    /**/

    return 0;
}

bool consecutivePairExists(int x)
{
    bool found = false;
    f(x);

    int i = 0;
    while (!found && (unsigned)i < chain.size())
    {
        if (
            chain.at(i) > 2 && 
            (find(chain.begin(), chain.end(), chain.at(i) - 1) != chain.end()
            || 
            find(chain.begin(), chain.end(), chain.at(i) + 1) != chain.end())
        )
            found = true;

        i++;
    }
    return found;
}

bool doesLoop(int x)
{
    if (!growsAt(x))
        return true;
    else 
        return false;
}

vector<int> f(int x, int call)
{
    if (call == 0)
    {
        chain.clear();
        loop.clear();
    }
    chain.push_back(x);

    if (x > 0)
    {
        if (x % 2 == 1)
        {
            if (growsAt(x))
            {
                loop.push_back(returnOverflow);
                // throw std::overflow_error("OVERFLOW\n");
            }
            else
            {
                if (find(chain.begin(), chain.end(), oddCoefficient * x + oddAddend) != chain.end())
                {
                    if (find(chain.begin(), chain.end(), oddCoefficient * x + oddAddend) == chain.begin())
                        loop = chain;
                    else
                    {
                        vector<int> Loop(find(chain.begin(), chain.end(), oddCoefficient * x + oddAddend), chain.end());
                        loop = Loop;
                    }
                }
                else
                    return f(oddCoefficient * x + oddAddend, call + 1);
            }
        }
        else if (x % 2 == 0)
        {
            if (find(chain.begin(), chain.end(), x / evenDivisor) != chain.end())
            {
                if (find(chain.begin(), chain.end(), x / evenDivisor) == chain.begin())
                    loop = chain;
                else
                {
                    vector<int> Loop(find(chain.begin(), chain.end(), x / evenDivisor), chain.end());
                    loop = Loop;
                }
            }
            else
                return f(x / evenDivisor, call + 1);
        }
    }
    return loop;
}

vector<int> f_inv(int x)
{
    vector<int> values;

    if (x % 6 == 0 || x % 6 == 1 || x % 6 == 2 || x % 6 == 3 || x % 6 == 5)
        values.push_back(2 * x);
    
    if (x % 6 == 4)
    {
        values.push_back(2 * x);
        values.push_back((x - 1) / 3);
    }
    return values;
}

vector<int> f_print(int x, int call)
{
    if (call == 0)
    {
        chain.clear();
        loop.clear();
    }
    chain.push_back(x);

    cout << "f(" << x << ") = ";
    if (x > 0)
    {
        if (x % 2 == 1)
        {
            if (growsAt(x))
            {
                loop.push_back(returnOverflow);
                // throw std::overflow_error("OVERFLOW\n");
            }
            else
            {
                cout << oddCoefficient * x + oddAddend << endl;
                if (find(chain.begin(), chain.end(), oddCoefficient * x + oddAddend) != chain.end())
                {
                    if (find(chain.begin(), chain.end(), oddCoefficient * x + oddAddend) == chain.begin())
                        loop = chain;
                    else
                    {
                        vector<int> Loop(find(chain.begin(), chain.end(), oddCoefficient * x + oddAddend), chain.end());
                        loop = Loop;
                    }
                }
                else
                    return f_print(oddCoefficient * x + oddAddend, call + 1);
            }
        }
        else if (x % 2 == 0)
        {
            cout << x / evenDivisor << endl;
            if (find(chain.begin(), chain.end(), x / evenDivisor) != chain.end())
            {
                if (find(chain.begin(), chain.end(), x / evenDivisor) == chain.begin())
                    loop = chain;
                else
                {
                    vector<int> Loop(find(chain.begin(), chain.end(), x / evenDivisor), chain.end());
                    loop = Loop;
                }
            }
            else
                return f_print(x / evenDivisor, call + 1);
        }
    }
    cout << "\nLOOP: ";
    printv(loop);
    return loop;
}

vector<int> getChain(int x, int call)
{
    if (call == 0)
    {
        chain.clear();
        loop.clear();
    }
    chain.push_back(x);

    if (x > 0)
    {
        if (x % 2 == 1)
        {
            if (growsAt(x))
            {
                chain.push_back(returnOverflow);
                // throw std::overflow_error("OVERFLOW\n");
            }
            else
            {
                if (find(chain.begin(), chain.end(), oddCoefficient * x + oddAddend) == chain.end())
                    return f(oddCoefficient * x + oddAddend, call + 1);
            }
        }
        else if (x % 2 == 0)
        {
            if (find(chain.begin(), chain.end(), x / evenDivisor) == chain.end())
                return f(x / evenDivisor, call + 1);
        }
    }
    return chain;
}

vector<int> getLayer(int n)
{
    vector<vector<int> > layers = getLayers(n);
    return layers.at(n);
}

vector<vector<int> > getLayers(int n)
{
    vector<vector<int> > layers;
    vector<int> newVec;
    vector<int> toBeInserted;
    vector<int> start(1,1);
    vector<int> two(1,2);
    layers.push_back(start);
    layers.push_back(two);

    for (int i = 1; i < n; i++)
    {
        layers.push_back(newVec);
        for (int j = 0; (unsigned)j < layers.at(i).size(); j++)
        {
            toBeInserted = f_inv(layers.at(i).at(j));
            layers.at(i + 1).insert
            (
                layers.at(i + 1).end(), 
                toBeInserted.begin(), 
                toBeInserted.end()
            );
        }
    }
    return layers;
}

vector<vector<int> > getLoops(int max)
{
    vector<vector<int> > loops;
    for (int x = 1; x < max; x++)
    {
        f(x);
        if (find(loops.begin(), loops.end(), loop) == loops.end())
            loops.push_back(loop);
    }
    return loops;
}

int getNumDrops(int x)
{
    int numDrops = 0;
    f(x);
    for (int i = 0; (unsigned)i < chain.size(); i++)
    {
        if (chain.at(i) % 2 == 0)
            numDrops++;
    }
    return numDrops;
}

int getNumInLayer(int n) { return getLayer(n).size(); }

int getNumJumps(int x)
{
    int numJumps = 0;
    f(x);
    for (int i = 0; (unsigned)i < chain.size(); i++)
    {
        if (chain.at(i) % 2 == 1)
            numJumps++;
    }
    return numJumps;
}

bool growsAt(int x)
{
    if (isOverflowMultiply(oddCoefficient, x)
    || isOverflowAdd(oddCoefficient * x, oddAddend) 
    // These cases stop the loop at the beginning, returning loop = <returnOverflow> in f. 
    // ex + o = o
    || (oddCoefficient % 2 == 0 && oddAddend % 2 == 1)
    // oo + e = o
    || (oddCoefficient % 2 == 1 && oddAddend % 2 == 0 && x % 2 == 1)
    // (2^n, 2^{n + 1}k, 2)
    || (isPowerOf2(oddCoefficient) && oddAddend % oddCoefficient * 2  == 0 && evenDivisor == 2)


    // // (4, 6(2k + 1), 2), does nothing?
    // || (oddCoefficient == 4 && oddAddend % 6 == 0 && oddAddend / 6 % 2 == 1 && evenDivisor == 2)
    // // (8, 2k, 2) [(8, 2k + 1, 2) is the 3rd case above]
    // || (oddCoefficient == 8 && evenDivisor == 2)
    )
        return true;
    return false;
}

bool growsUpTo(int n)
{
    for (int x = 0; x < n; x++)
    {
        if(!growsAt(x))
            return false;
    }
    return true;
}

bool growsOver(int n, int m)
{
    for (int x = 0; x < n; x++)
    {
        if(!growsAt(x))
            return false;
    }
    return true;
}

bool intersectOffMainChannel(int x, int y)
{
    f(x);
    vector<int> xChain = chain;
    f(y);
    vector<int> yChain = chain;

    for (int i = xChain.size() - 1; i > -1; i--)
    {
        if (!isPowerOf2(xChain.at(i)) && isInChain(xChain.at(i), yChain))
            return true;
    }
    return false;
}

vector<int> intersectv(vector<int> v1, vector<int> v2)
{
    vector<int> newVec;
    if (v1.size() < v2.size())
    {
        for (int i = 0; (unsigned)i < v1.size(); i++)
        {
            if (find(v2.begin(), v2.end(), v1.at(i)) != v2.end())
                newVec.push_back(v1.at(i));
        }
    }
    else
    {
        for (int i = 0; (unsigned)i < v2.size(); i++)
        {
            if (find(v1.begin(), v1.end(), v2.at(i)) != v1.end())
                newVec.push_back(v2.at(i));
        }
    }
    return newVec;
}

bool isPowerOf2(int x)
{
    while (x % 2 == 0) { x = x / evenDivisor; }
    if (x != 1)
        return false;
    else
        return true;
}

bool isInChain(int x, int y)
{
    f(x);

    if (find(chain.begin(), chain.end(), y) != chain.end())
        return true;
    else
        return false;
}

bool isInChain(int x, vector<int> chain)
{
    if (find(chain.begin(), chain.end(), x) != chain.end())
        return true;
    else
        return false;
}

bool isOverflowAdd(int x, int y)
{
    int result = x + y;
    if (x > 0 && y > 0 && result < 0)
        return true;
    if (x < 0 && y < 0 && result > 0)
        return true;
    return false;
}

bool isOverflowMultiply(int x, int y)
{
    if (x == 0 || y == 0)
        return false;
    
    int result = x * y;
    if (x == result / y)
        return false;
    else
        return true;
}

bool loopsAt(int x)
{
    vector<int> v = f(x);
    if (find(v.begin(), v.end(), x) != v.end())
        return true;
    return false;
}

bool loopsUpTo(int n)
{
    for (int x = 0; x < n; x++)
    {
        if(!loopsAt(x))
            return false;
    }
    return true;
}

bool loopsOver(int n, int m)
{
    for (int x = 0; x < n; x++)
    {
        if(!loopsAt(x))
            return false;
    }
    return true;
}

int posInChain(int x, int y)
{
    f(x);

    if (isInChain(x, y))
        return find(chain.begin(), chain.end(), y) - chain.begin();
    else
        return -1;
}

void printv(vector<int> v)
{
    for (int i = 0; (unsigned)i < v.size(); i++)
    {
        cout << v.at(i);
        if ((unsigned)i < v.size() - 1)
            cout << ", ";
    }
    cout << endl;
}

void printv2d(vector<vector<int> > v)
{
    for (int i = 0; (unsigned)i < v.size(); i++)
    {
        for (int j = 0; (unsigned)j < v.at(i).size(); j++)
        {
            cout << v.at(i).at(j);
            if ((unsigned)j < v.at(i).size() - 1)
                cout << ", ";
        }
        if ((unsigned)i < v.size() - 1)
            cout << endl;
    }
    cout << endl;
}

bool separatedPairExists(int x, int s)
{
    bool found = false;
    f(x);
    int i = 0;
    while (!found && (unsigned)i < chain.size())
    {
        if (
            chain.at(i) > 2 && 
            (find(chain.begin(), chain.end(), chain.at(i) - s) != chain.end()
            || 
            find(chain.begin(), chain.end(), chain.at(i) + s) != chain.end())
        )
            found = true;

        i++;
    }
    return found;
}

bool stabilizesAt(int x)
{
    if (growsAt(x))
        return false;

    vector<int> intersection = intersectv(f(1), f(2));
    for (int i = 3; i < 1000; i++)
    {
        intersection = intersectv(intersection, f(i));
        if (find(intersection.begin(), intersection.end(), x) == intersection.end())
            return false;
    }
    return true;
}

bool stabilizesUpTo(int n)
{
    for (int x = 0; x < n; x++)
    {
        if(stabilizesAt(x))
            return true;
    }
    return false;
}

bool stabilizesOver(int n, int m)
{
    for (int x = 0; x < n; x++)
    {
        if(!stabilizesAt(x))
            return false;
    }
    return true;
}

void writeStatus(int a, int b, int max)
{
    bool grows = false, loops = false;
    vector<string> lines;
    string line;
    grows = false;
    loops = false;
    oddCoefficient = a;
    oddAddend = b;
    if (oddCoefficient % 2 == 1 || oddAddend % 2 == 0)
    {
        cout << "(" << oddCoefficient << ", " << oddAddend << ", " << evenDivisor << ")\n";

        string fileName = to_string(oddCoefficient) + "-" + to_string(oddAddend) + "-" + to_string(evenDivisor) + ".txt";
        outFile.open(fileName);
        outFile << oddCoefficient << ", " << oddAddend << ", " << evenDivisor << endl;
        for (int x = 1; x < max; x++)
        {
            f(x);
            outFile << x;
            if (growsAt(x) || loop.at(loop.size() - 1) == returnOverflow)
            {
                grows = true;
                outFile << ";grows\n";
            }
            else
            {
                loops = true;
                outFile << ";loops;";
                writev(loop);
            }
        }
        if (grows || loops)
        {
            inFile.open(fileName);
            while (!inFile.eof())
            {
                getline(inFile, line);
                lines.push_back(line);
            }
            inFile.close();
            outFile.open(fileName);
            if (grows)
                lines.insert(lines.begin(), "grows");
            if (loops)
                lines.insert(lines.begin(), "loops");
            outFile.close();
        }
        outFile.open(fileName);
        for (int i = 0; (unsigned)i < lines.size(); i++)
        {
            outFile << lines.at(i);
            if ((unsigned)i < lines.size() - 1)
                outFile << endl;
        }
        outFile.close();
    }
}

void writev(vector<int> v)
{
    for (int i = 0; (unsigned)i < v.size(); i++)
    {
        outFile << v.at(i);
        if ((unsigned)i < v.size() - 1)
            outFile << ", ";
    }
    outFile << endl;
}